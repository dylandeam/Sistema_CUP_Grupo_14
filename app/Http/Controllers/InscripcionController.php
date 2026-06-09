<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Models\Inscripcion_Carrera;
use App\Models\Pago;
use App\Models\Postulante;
use App\Models\Requisitos_Postulante;
use App\Models\Gestion;
use App\Models\Modalidad;
use App\Models\Carrera;
use App\Models\Turno;
use App\Models\User;
use App\Services\GrupoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class InscripcionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inscripciones = Inscripcion::with('postulante')->orderBy('id', 'desc')->get();

        return view('admin.inscripciones.index', compact('inscripciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $postulantes = Postulante::orderBy('nombre')->get();
        $gestions = Gestion::where('estado', 'Activa')->orderBy('semestre')->get();
        $modalidades = Modalidad::orderBy('nombre')->get();
        $carreras = \App\Models\Carrera::orderBy('nombre')->get();
        $turnos = Turno::orderBy('nombre')->get();
        $costo_inscripcion = config('inscripcion.costo');

        return view('admin.inscripciones.create', compact('postulantes', 'gestions', 'modalidades', 'carreras', 'turnos', 'costo_inscripcion'));
    }

    public function showImportForm()
    {
        return view('admin.postulantes.import');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $postulante = Postulante::where('ci', $request->ci)->first();

        $emailRules = ['required', 'email'];
        if ($postulante && $postulante->usuario_id) {
            $emailRules[] = Rule::unique('users', 'email')->ignore($postulante->usuario_id);
        } else {
            $emailRules[] = Rule::unique('users', 'email');
        }

        $request->validate([
            'gestion_id' => 'required|exists:gestions,id',
            'modalidad_id' => 'required|exists:modalidades,id',
            'turno_id' => 'required|exists:turnos,id',
            'estado' => 'required|in:PENDIENTE,INSCRITO',
            'fecha_insc' => 'required|date',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'ci' => 'required|string|max:255',
            'email' => $emailRules,
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:M,F,O',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'required|string|max:255',
            'colegio' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'carrera_primera_opcion_id' => 'required|exists:carreras,id|different:carrera_segunda_opcion_id',
            'carrera_segunda_opcion_id' => 'required|exists:carreras,id',
            'monto_pago' => 'required_if:estado_pago,CONFIRMADO|nullable|numeric|min:0',
            'fecha_pago' => 'required_if:estado_pago,CONFIRMADO|nullable|date',
            'estado_pago' => 'required|in:PENDIENTE,CONFIRMADO',
            'comprobante' => 'nullable|string|max:255',
            'generar_pago' => 'nullable|in:0,1',
        ]);

        if (!$postulante) {
            $user = User::create([
                'name' => $request->nombre.' '.$request->apellidos,
                'email' => $request->email,
                'password' => Hash::make($request->ci),
            ]);
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('POSTULANTE');
            }

            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('postulantes', 'public');
            }

            $codigo = strtoupper(substr($request->apellidos, 0, 1)).strtoupper(substr($request->nombre, 0, 1)).substr(preg_replace('/\D/', '', $request->ci), 0, 4);
            if (Postulante::where('codigo', $codigo)->exists()) {
                $codigo .= time();
            }

            $postulante = Postulante::create([
                'codigo' => $codigo,
                'usuario_id' => $user->id,
                'nombre' => $request->nombre,
                'apellidos' => $request->apellidos,
                'ci' => $request->ci,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'sexo' => $request->sexo,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'colegio' => $request->colegio,
                'ciudad' => $request->ciudad,
                'foto' => $fotoPath,
            ]);
        } else {
            $user = $postulante->user;
            if ($user && $user->email !== $request->email) {
                $user->update(['email' => $request->email]);
            }
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('postulantes', 'public');
                $postulante->update(['foto' => $fotoPath]);
            }
        }

        $inscripcion = new Inscripcion();
        $inscripcion->estado = $request->estado_pago === 'CONFIRMADO' ? 'INSCRITO' : 'PENDIENTE';
        $inscripcion->fecha_insc = $request->fecha_insc;
        $inscripcion->costo = config('inscripcion.costo');
        $inscripcion->postulante_codigo = $postulante->codigo;
        $inscripcion->gestion_id = $request->gestion_id;
        $inscripcion->modalidad_id = $request->modalidad_id;
        $inscripcion->turno_id = $request->turno_id;
        $inscripcion->save();

        GrupoService::generarGrupoParaInscripcion($inscripcion);

        Requisitos_Postulante::updateOrCreate([
            'postulante_codigo' => $postulante->codigo,
        ], [
            'fotocopia_ci' => $request->boolean('fotocopia_ci'),
            'certificado_nacimiento' => $request->boolean('certificado_nacimiento'),
            'titulo_bachiller' => $request->boolean('titulo_bachiller'),
            'libreta_colegio' => $request->boolean('libreta_colegio'),
        ]);

        Inscripcion_Carrera::create([
            'orden_pref' => 1,
            'inscripcion_id' => $inscripcion->id,
            'carrera_id' => $request->carrera_primera_opcion_id,
        ]);

        Inscripcion_Carrera::create([
            'orden_pref' => 2,
            'inscripcion_id' => $inscripcion->id,
            'carrera_id' => $request->carrera_segunda_opcion_id,
        ]);

        if ($request->filled('monto_pago')) {
            Pago::create([
                'monto' => $request->monto_pago,
                'fecha' => $request->fecha_pago,
                'estado' => $request->estado_pago,
                'comprobante' => $request->comprobante,
                'inscripcion_id' => $inscripcion->id,
            ]);
        }

        return redirect()->route('admin.inscripciones.index')
            ->with('mensaje', 'Inscripción registrada correctamente')
            ->with('icono', 'success');
    }

    /**
     * Send PayPal payment instructions by email.
     */
    public function sendPaypalEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'costo' => 'required|numeric|min:0',
        ]);

        $total = number_format($request->costo, 2, '.', '');

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $data = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => config('paypal.currency'),
                        'value' => $total,
                    ],
                    'description' => 'Pago de inscripción al proceso de admisión',
                ],
            ],
            'application_context' => [
                'cancel_url' => route('web.paypal.cancelar'),
                'return_url' => route('web.paypal.gracias'),
            ],
        ];

        try {
            $response = $provider->createOrder($data);

            if (empty($response['id'])) {
                throw new \Exception('No se pudo crear la orden de PayPal.');
            }

            $approveLink = null;
            foreach ($response['links'] ?? [] as $link) {
                if (isset($link['rel']) && $link['rel'] === 'approve') {
                    $approveLink = $link['href'];
                    break;
                }
            }

            if (!$approveLink) {
                throw new \Exception('No se encontró el enlace de aprobación de PayPal.');
            }

            $messageBody = "Hola,\n\n" .
                "Se ha generado una solicitud de pago para la inscripción.\n" .
                "Monto: $" . $total . " " . config('paypal.currency') . "\n" .
                "Por favor utilice el siguiente enlace para completar el pago con PayPal:\n" .
                "$approveLink\n\n" .
                "Este enlace abrirá PayPal para que pueda iniciar sesión, pagar con su cuenta o con tarjeta.\n\n" .
                "Gracias.";

            Mail::raw($messageBody, function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Solicitud de pago de inscripción');
            });

            return response()->json(['message' => 'Correo de pago enviado a ' . $request->email], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al generar el enlace de pago: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Inscripcion $inscripcion)
    {
        $requisitos = Requisitos_Postulante::firstWhere('postulante_codigo', $inscripcion->postulante_codigo);
        $pago = Pago::where('inscripcion_id', $inscripcion->id)->latest()->first();
        $primeraCarrera = Inscripcion_Carrera::where('inscripcion_id', $inscripcion->id)->where('orden_pref', 1)->first();
        $segundaCarrera = Inscripcion_Carrera::where('inscripcion_id', $inscripcion->id)->where('orden_pref', 2)->first();

        return view('admin.inscripciones.show', compact(
            'inscripcion',
            'requisitos',
            'pago',
            'primeraCarrera',
            'segundaCarrera'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inscripcion $inscripcion)
    {
        $postulante = $inscripcion->postulante;
        $gestions = Gestion::where('estado', 'Activa')->orderBy('semestre')->get();
        $modalidades = Modalidad::orderBy('nombre')->get();
        $carreras = Carrera::orderBy('nombre')->get();
        $turnos = Turno::orderBy('nombre')->get();
        $costo_inscripcion = config('inscripcion.costo');

        $requisitos = Requisitos_Postulante::firstWhere('postulante_codigo', $inscripcion->postulante_codigo);
        $pago = Pago::where('inscripcion_id', $inscripcion->id)->latest()->first();

        $primeraCarrera = Inscripcion_Carrera::where('inscripcion_id', $inscripcion->id)
            ->where('orden_pref', 1)
            ->first();
        $segundaCarrera = Inscripcion_Carrera::where('inscripcion_id', $inscripcion->id)
            ->where('orden_pref', 2)
            ->first();

        return view('admin.inscripciones.edit', compact(
            'inscripcion',
            'postulante',
            'gestions',
            'modalidades',
            'carreras',
            'turnos',
            'costo_inscripcion',
            'requisitos',
            'pago',
            'primeraCarrera',
            'segundaCarrera'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inscripcion $inscripcion)
    {
        $postulante = $inscripcion->postulante;

        $request->validate([
            'gestion_id' => 'required|exists:gestions,id',
            'modalidad_id' => 'required|exists:modalidades,id',
            'turno_id' => 'required|exists:turnos,id',
            'estado' => 'required|in:PENDIENTE,INSCRITO',
            'fecha_insc' => 'required|date',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'ci' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($postulante?->usuario_id)],
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:M,F,O',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'required|string|max:255',
            'colegio' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'carrera_primera_opcion_id' => 'required|exists:carreras,id|different:carrera_segunda_opcion_id',
            'carrera_segunda_opcion_id' => 'required|exists:carreras,id',
        ]);

        if ($postulante) {
            $user = $postulante->user;
            if ($user && $user->email !== $request->email) {
                $user->update(['email' => $request->email]);
            }

            $postulante->update([
                'nombre' => $request->nombre,
                'apellidos' => $request->apellidos,
                'ci' => $request->ci,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'sexo' => $request->sexo,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'colegio' => $request->colegio,
                'ciudad' => $request->ciudad,
            ]);

            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('postulantes', 'public');
                $postulante->update(['foto' => $fotoPath]);
            }
        }

        $inscripcion->update([
            'estado' => $request->estado,
            'fecha_insc' => $request->fecha_insc,
            'costo' => config('inscripcion.costo'),
            'gestion_id' => $request->gestion_id,
            'modalidad_id' => $request->modalidad_id,
            'turno_id' => $request->turno_id,
        ]);

        GrupoService::generarGrupoParaInscripcion($inscripcion);

        Requisitos_Postulante::updateOrCreate(
            ['postulante_codigo' => $inscripcion->postulante_codigo],
            [
                'fotocopia_ci' => $request->boolean('fotocopia_ci'),
                'certificado_nacimiento' => $request->boolean('certificado_nacimiento'),
                'titulo_bachiller' => $request->boolean('titulo_bachiller'),
                'libreta_colegio' => $request->boolean('libreta_colegio'),
            ]
        );

        Inscripcion_Carrera::updateOrCreate(
            ['inscripcion_id' => $inscripcion->id, 'orden_pref' => 1],
            ['carrera_id' => $request->carrera_primera_opcion_id]
        );
        Inscripcion_Carrera::updateOrCreate(
            ['inscripcion_id' => $inscripcion->id, 'orden_pref' => 2],
            ['carrera_id' => $request->carrera_segunda_opcion_id]
        );

        return redirect()->route('admin.inscripciones.index')
            ->with('mensaje', 'Inscripción actualizada correctamente')
            ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inscripcion $inscripcion)
    {
        DB::transaction(function () use ($inscripcion) {
            Pago::where('inscripcion_id', $inscripcion->id)->delete();
            Inscripcion_Carrera::where('inscripcion_id', $inscripcion->id)->delete();
            Requisitos_Postulante::where('postulante_codigo', $inscripcion->postulante_codigo)->delete();
            $inscripcion->delete();
        });

        return redirect()->route('admin.inscripciones.index')
            ->with('mensaje', 'Inscripción eliminada correctamente junto con su pago, requisitos y postulaciones a carreras.')
            ->with('icono', 'success');
    }

}
