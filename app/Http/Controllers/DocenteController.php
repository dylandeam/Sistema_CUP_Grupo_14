<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\Docente_Materia;
use App\Models\User;
use App\Models\Materia;
use App\Models\RequisitosDocente;
use App\Models\DocenteMateria;
use App\Models\Requisitos_Docente;
use App\Services\BitacoraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class DocenteController extends Controller
{
    /**
     * Listado de docentes
     */
    public function index()
    {
        $docentes = Docente::with(['user.roles', 'materias', 'asignaciones'])->get();
        return view('admin.docentes.index', compact('docentes'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        $roles = Role::all();
        $materias = Materia::orderBy('nombre')->get();

        return view('admin.docentes.create', compact('roles', 'materias'));
    }

    /**
     * Guardar nuevo docente con requisitos y materia
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'            => 'required|string|max:255',
            'apellido'          => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'ci'                => 'required|unique:docentes,ci',
            'fecha_nacimiento'  => 'required|date',
            'telefono'          => 'nullable|string|max:20',
            'direccion'         => 'nullable|string|max:255',
            'estado'            => 'required|in:activo,baja',
            'foto'              => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nombre_titulo'     => 'required|string|max:150',
            'nombre_maestria'   => 'required|string|max:150',
            'nombre_diplomado'  => 'required|string|max:150',
            'materia_id'        => 'required|exists:materias,id',
        ], [
            'ci.unique' => 'El CI ya está registrado en otro docente.',
            'email.unique' => 'El correo ya está en uso.',
        ]);

        $titulo = ! empty($request->nombre_titulo);
        $maestria = ! empty($request->nombre_maestria);
        $diplomado = ! empty($request->nombre_diplomado);

        if (! $titulo || ! $maestria || ! $diplomado) {
            return back()->withInput()->withErrors(['requisitos' => 'No cumple con los 3 requisitos y no puede ser contratado.']);
        }

        $area = $this->inferirArea(
            $request->nombre_titulo,
            $request->nombre_maestria,
            $request->nombre_diplomado
        );

        if (! $area) {
            return back()->withInput()->withErrors(['requisitos' => 'Los títulos ingresados no son afines a un área válida y no puede ser contratado.']);
        }

        $materia = Materia::findOrFail($request->materia_id);

        // Crear usuario
        $usuario = User::create([
            'name'     => $request->nombre . ' ' . $request->apellido,
            'email'    => $request->email,
            'password' => Hash::make($request->ci),
        ]);
        $usuario->assignRole('DOCENTE');

        // Generar código
        $codigo = strtoupper(substr($request->apellido, 0, 1)) .
                  strtoupper(substr($request->nombre, 0, 1)) .
                  substr($request->ci, 0, 4);

        // Foto
        $fotoPath = $request->hasFile('foto')
            ? $request->file('foto')->store('docentes', 'public')
            : null;

        // Crear docente
        $docente = Docente::create([
            'usuario_id'      => $usuario->id,
            'codigo'          => $codigo,
            'nombre'          => $request->nombre,
            'apellido'        => $request->apellido,
            'ci'              => $request->ci,
            'fecha_nacimiento'=> $request->fecha_nacimiento,
            'telefono'        => $request->telefono,
            'direccion'       => $request->direccion,
            'foto'            => $fotoPath,
        ]);

        // Guardar requisitos
        Requisitos_Docente::create([
            'docente_codigo'   => $docente->codigo,
            // 'titulo'           => $titulo,
            'nombre_titulo'    => $request->nombre_titulo,
            // 'maestria'         => $maestria,
            'nombre_maestria'  => $request->nombre_maestria,
            // 'diplomado'        => $diplomado,
            'nombre_diplomado' => $request->nombre_diplomado,
            // 'area_especialidad'=> $area,
        ]);

        // Guardar asignación de materia
        Docente_Materia::create([
            'codigo_docente' => $docente->codigo,
            'materia_id'     => $materia->id,
            'estado'         => $request->estado,
        ]);

        BitacoraService::registrar('Creó docente y le asignó materia');

        return redirect()->route('admin.docentes.index')
            ->with('mensaje', 'Docente registrado correctamente con requisitos y materia')
            ->with('icono', 'success');
    }

    /**
     * Mostrar detalle de un docente
     */
    public function show(Docente $docente)
    {
        $docente->load(['user.roles', 'materias', 'requisitos']);
        
        // Obtener cargas horarias de la gestión activa
        $gestionActiva = \App\Models\Gestion::where('estado', 'Activa')->first();
        $cargasHorarias = $gestionActiva 
            ? \App\Models\CargaHoraria::where('docente_codigo', $docente->codigo)
                ->where('gestion_id', $gestionActiva->id)
                ->with(['grupo', 'materia'])
                ->get()
            : collect();
        
        // Contar grupos únicos asignados
        $gruposAsignados = $cargasHorarias->pluck('grupo_id')->unique()->count();

        return view('admin.docentes.show', compact('docente', 'gruposAsignados', 'cargasHorarias'));
    }

    private function inferirArea(string $titulo, string $maestria, string $diplomado): ?string
    {
        $texto = mb_strtolower(trim($titulo . ' ' . $maestria . ' ' . $diplomado));

        if (preg_match('/matem[aá]tica|álgebra|estad[ií]stica|geometr[ií]a|probabilidad|cálculo|matemáticas/', $texto)) {
            return 'matematicas';
        }

        if (preg_match('/computaci[oó]n|programaci[oó]n|sistemas|software|inform[aá]tica|tecnolog[ií]a/', $texto)) {
            return 'computacion';
        }

        if (preg_match('/f[ií]sica|ingenier[ií]a|mec[aá]nica|electr[oó]nica|qu[ií]mica|biomec[aá]nica/', $texto)) {
            return 'fisica';
        }

        if (preg_match('/ingl[eé]s|idioma|lengua extranjera|traducci[oó]n|literatura inglesa|filolog[ií]a/', $texto)) {
            return 'ingles';
        }

        return null;
    }

    /**
     * Formulario de edición
     */
    public function edit(Docente $docente)
    {
        $roles = Role::all();
        $materias = Materia::whereIn('nombre', ['Matemática','Computación','Física','Inglés'])->get();
        $asignacion = Docente_Materia::where('codigo_docente', $docente->codigo)->first();

        return view('admin.docentes.edit', compact('docente','roles','materias','asignacion'));
    }

    /**
     * Actualizar docente
     */
    public function update(Request $request, Docente $docente)
    {
        $request->validate([
            'nombre'            => 'required|string|max:255',
            'apellido'          => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email,' . $docente->usuario_id,
            'ci'                => 'required|unique:docentes,ci,' . $docente->codigo . ',codigo',
            'fecha_nacimiento'  => 'required|date',
            'telefono'          => 'nullable|string|max:20',
            'direccion'         => 'nullable|string|max:255',
            'estado'            => 'required|in:activo,baja',
            'foto'              => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nombre_titulo'     => 'required|string|max:150',
            'nombre_maestria'   => 'required|string|max:150',
            'nombre_diplomado'  => 'required|string|max:150',
            'materia_id'        => 'required|exists:materias,id',
        ], [
            'ci.unique' => 'El CI ya está registrado en otro docente.',
            'email.unique' => 'El correo ya está en uso.',
        ]);

        $requisitos = Requisitos_Docente::where('docente_codigo', $docente->codigo)->first();
        $asignacion = Docente_Materia::where('codigo_docente', $docente->codigo)->first();
        $oldCodigo = $docente->codigo;

        // Actualizar usuario
        $usuario = $docente->user;
        $usuario->update([
            'name'  => $request->nombre . ' ' . $request->apellido,
            'email' => $request->email,
        ]);
        $usuario->syncRoles(['DOCENTE']);

        // Regenerar código
        $codigo = strtoupper(substr($request->apellido,0,1)) .
                  strtoupper(substr($request->nombre,0,1)) .
                  substr($request->ci,0,4);

        // Foto
        $fotoPath = $docente->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('docentes', 'public');
        }

        $area = $this->inferirArea(
            $request->nombre_titulo,
            $request->nombre_maestria,
            $request->nombre_diplomado
        );

        // Actualizar docente
        $docente->update([
            'codigo'          => $codigo,
            'nombre'          => $request->nombre,
            'apellido'        => $request->apellido,
            'ci'              => $request->ci,
            'fecha_nacimiento'=> $request->fecha_nacimiento,
            'telefono'        => $request->telefono,
            'direccion'       => $request->direccion,
            'foto'            => $fotoPath,
        ]);

        if ($oldCodigo !== $codigo) {
            Requisitos_Docente::where('docente_codigo', $oldCodigo)
                ->update(['docente_codigo' => $codigo]);

            Docente_Materia::where('codigo_docente', $oldCodigo)
                ->update(['codigo_docente' => $codigo]);
        }

        if (! $requisitos) {
            $requisitos = new Requisitos_Docente();
        }
        $requisitos->fill([
            'docente_codigo'   => $codigo,
            // 'titulo'           => true,
            'nombre_titulo'    => $request->nombre_titulo,
            // 'maestria'         => true,
            'nombre_maestria'  => $request->nombre_maestria,
            // 'diplomado'        => true,
            'nombre_diplomado' => $request->nombre_diplomado,
            // 'area_especialidad'=> $area,
        ]);
        $requisitos->save();

        if (! $asignacion) {
            $asignacion = new Docente_Materia();
            $asignacion->codigo_docente = $codigo;
        }
        $asignacion->materia_id = $request->materia_id;
        $asignacion->estado = $request->estado;
        $asignacion->codigo_docente = $codigo;
        $asignacion->save();

        BitacoraService::registrar('Actualizó docente ' . $docente->codigo);

        return redirect()->route('admin.docentes.index')
            ->with('mensaje', 'Docente actualizado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Eliminar docente
     */
    public function destroy(Docente $docente)
    {
        if ($docente->foto && Storage::disk('public')->exists($docente->foto)) {
            Storage::disk('public')->delete($docente->foto);
        }

        if ($docente->user) {
            $docente->user->delete();
        }

        $docente->delete();

        BitacoraService::registrar('Eliminó docente ' . $docente->codigo);

        return redirect()->route('admin.docentes.index')
            ->with('mensaje', 'Docente eliminado correctamente')
            ->with('icono', 'success');
    }

    public function showImportForm()
    {
        return view('admin.docentes.import');
    }

    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv,txt|max:2048',
    ]);

    $sheets = Excel::toArray([], $request->file('file'));

    if (empty($sheets) || empty($sheets[0])) {
        return back()->with('mensaje', '❌ El archivo está vacío o no contiene datos válidos.')->with('icono', 'danger');
    }

    $rows = $sheets[0];
    $header = array_map(function ($value) {
        return strtolower(trim((string) $value));
    }, $rows[0]);

    $headerMap = array_flip($header);

    $getIndex = function (array $names) use ($headerMap) {
        foreach ($names as $name) {
            $key = strtolower(trim($name));
            if (isset($headerMap[$key])) {
                return $headerMap[$key];
            }
        }
        return null;
    };

    $indexes = [
        'nombre'           => $getIndex(['nombre']),
        'apellido'         => $getIndex(['apellido']),
        'ci'               => $getIndex(['ci']),
        'fecha_nacimiento' => $getIndex(['fecha_nacimiento', 'fecha nacimiento']),
        'email'            => $getIndex(['email', 'correo']),
        'telefono'         => $getIndex(['telefono', 'teléfono']),
        'direccion'        => $getIndex(['direccion', 'dirección']),
        'rol'              => $getIndex(['rol']),
        'nombre_titulo'    => $getIndex(['nombre_titulo', 'titulo', 'título']),
        'nombre_maestria'  => $getIndex(['nombre_maestria', 'maestria', 'maestría']),
        'nombre_diplomado' => $getIndex(['nombre_diplomado', 'diplomado']),
        'materia'          => $getIndex(['materia', 'materia_nombre', 'materia id', 'materia_id']),
        'estado'           => $getIndex(['estado', 'estado_contratacion', 'estado de contratación']),
    ];

    $requiredHeaders = [
        'nombre',
        'apellido',
        'ci',
        'fecha_nacimiento',
        'email',
        'materia',
        'nombre_titulo',
        'nombre_maestria',
        'nombre_diplomado',
    ];

    $missingHeaders = [];
    foreach ($requiredHeaders as $required) {
        if ($indexes[$required] === null) {
            $missingHeaders[] = $required;
        }
    }

    if (!empty($missingHeaders)) {
        return back()->with('mensaje', '❌ Faltan columnas obligatorias en el archivo: '.implode(', ', $missingHeaders).' .')->with('icono', 'danger');
    }

    $errores = [];
    $filasValidas = [];
    $ciUsados = [];
    $emailUsados = [];

    foreach ($rows as $fila => $row) {
        if ($fila === 0) {
            continue;
        }

        $nombre          = trim((string) ($row[$indexes['nombre']] ?? ''));
        $apellido        = trim((string) ($row[$indexes['apellido']] ?? ''));
        $ci              = preg_replace('/[^0-9]/', '', (string) ($row[$indexes['ci']] ?? ''));
        $email           = trim((string) ($row[$indexes['email']] ?? ''));
        $telefono        = preg_replace('/[^0-9]/', '', (string) ($row[$indexes['telefono']] ?? '')) ?: null;
        $direccion       = trim((string) ($row[$indexes['direccion']] ?? ''));
        $rol             = trim((string) ($row[$indexes['rol']] ?? 'DOCENTE')) ?: 'DOCENTE';
        $nombreTitulo    = trim((string) ($row[$indexes['nombre_titulo']] ?? ''));
        $nombreMaestria  = trim((string) ($row[$indexes['nombre_maestria']] ?? ''));
        $nombreDiplomado = trim((string) ($row[$indexes['nombre_diplomado']] ?? ''));
        $materiaValor    = trim((string) ($row[$indexes['materia']] ?? ''));
        $estadoRaw       = trim((string) ($row[$indexes['estado']] ?? 'activo'));

        $fechaRaw = $row[$indexes['fecha_nacimiento']] ?? '';
        if (is_numeric($fechaRaw) && $fechaRaw > 0) {
            try {
                $fechaNacimiento = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float)$fechaRaw)->format('Y-m-d');
            } catch (\Exception $e) {
                $fechaNacimiento = '';
            }
        } else {
            $fechaNacimiento = trim((string) $fechaRaw);
        }

        $estado = strtolower($estadoRaw);
        if ($estado === 'contratado') {
            $estado = 'activo';
        }

        $filaData = [
            'nombre'           => $nombre,
            'apellido'         => $apellido,
            'ci'               => $ci,
            'fecha_nacimiento' => $fechaNacimiento,
            'email'            => $email,
            'telefono'         => $telefono,
            'direccion'        => $direccion,
            'rol'              => $rol,
            'nombre_titulo'    => $nombreTitulo,
            'nombre_maestria'  => $nombreMaestria,
            'nombre_diplomado' => $nombreDiplomado,
            'materia_nombre'   => $materiaValor,
            'estado'           => $estado,
        ];

        if (in_array($ci, $ciUsados)) {
            $errores[] = "❌ Error en fila ".($fila + 1).": El CI $ci está duplicado en el archivo.";
            continue;
        }
        if (in_array($email, $emailUsados)) {
            $errores[] = "❌ Error en fila ".($fila + 1).": El Email $email está duplicado en el archivo.";
            continue;
        }

        $ciUsados[]    = $ci;
        $emailUsados[] = $email;

        $validator = Validator::make($filaData, [
            'nombre'           => 'required|string|max:255',
            'apellido'         => 'required|string|max:255',
            'ci'               => 'required|unique:docentes,ci',
            'email'            => 'required|email|unique:users,email',
            'fecha_nacimiento' => 'required|date',
            'telefono'         => 'nullable|string|max:20',
            'direccion'        => 'nullable|string|max:255',
            'nombre_titulo'    => 'required|string|max:150',
            'nombre_maestria'  => 'required|string|max:150',
            'nombre_diplomado' => 'required|string|max:150',
            'materia_nombre'   => 'required|string',
            'estado'           => 'required|in:activo,baja',
        ], [
            'nombre.required'           => 'El nombre es obligatorio.',
            'nombre.max'                => 'El nombre no puede superar 255 caracteres.',
            'apellido.required'         => 'El apellido es obligatorio.',
            'apellido.max'              => 'El apellido no puede superar 255 caracteres.',
            'ci.required'               => 'El CI es obligatorio.',
            'ci.unique'                 => 'El CI ya está registrado en el sistema.',
            'email.required'            => 'El email es obligatorio.',
            'email.email'               => 'El email no tiene un formato válido.',
            'email.unique'              => 'El email ya está registrado en el sistema.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date'     => 'La fecha de nacimiento no tiene un formato válido (use YYYY-MM-DD).',
            'nombre_titulo.required'    => 'El título es obligatorio.',
            'nombre_titulo.max'         => 'El título no puede superar 150 caracteres.',
            'nombre_maestria.required'  => 'La maestría es obligatoria.',
            'nombre_maestria.max'       => 'La maestría no puede superar 150 caracteres.',
            'nombre_diplomado.required' => 'El diplomado es obligatorio.',
            'nombre_diplomado.max'      => 'El diplomado no puede superar 150 caracteres.',
            'materia_nombre.required'   => 'La materia es obligatoria.',
            'estado.required'           => 'El estado es obligatorio.',
            'estado.in'                 => "El estado debe ser 'activo' o 'baja'.",
        ]);

        if ($validator->fails()) {
            $errores[] = "❌ Error en fila ".($fila + 1).": ".implode(' | ', $validator->errors()->all());
            continue;
        }

        $camposAcademicos = [
            $nombreTitulo,
            $nombreMaestria,
            $nombreDiplomado,
        ];

        $areasValidas = [
            '/matem[aá]t|[aá]lgebra|estad[ií]st|geometr|probabilid|c[aá]lculo|trigonometr/i',
            '/computac|programac|sistemas|software|inform[aá]t|tecnolog|redes|base.*dato|dato.*base/i',
            '/f[ií]sic|ingenier|mec[aá]n|electr[oó]n|qu[ií]mic|biomec[aá]n|termodin/i',
            '/ingl[eé]|idioma|lengua.*extran|extran.*lengua|traducc|literatura.*ingl|filolog|ling[üu][ií]st|ense[nñ]anza.*idioma|idioma.*ense[nñ]anza/i',
        ];

        $perteneceAlArea = false;
        foreach ($camposAcademicos as $campo) {
            foreach ($areasValidas as $regex) {
                if (preg_match($regex, $campo)) {
                    $perteneceAlArea = true;
                    break 2;
                }
            }
        }

        if (!$perteneceAlArea) {
            $errores[] = "❌ Error en fila ".($fila + 1).": Los requisitos académicos no corresponden a un área válida (Matemática, Computación, Física o Inglés). El docente no puede ser contratado.";
            continue;
        }

        $materia = null;
        if (is_numeric($materiaValor) && $materiaValor !== '') {
            $materia = Materia::find($materiaValor);
        }
        if (!$materia && $materiaValor !== '') {
            $materia = Materia::whereRaw('LOWER(nombre) = ?', [mb_strtolower($materiaValor)])->first();
        }

        if (!$materia) {
            $errores[] = "❌ Error en fila ".($fila + 1).": La materia '{$materiaValor}' no existe en el sistema.";
            continue;
        }

        $filaData['materia_id'] = $materia->id;
        $filasValidas[] = $filaData;
    }

    if (!empty($errores)) {
        return back()->with('mensaje', implode('<br>', $errores))->with('icono', 'danger');
    }

    DB::transaction(function () use ($filasValidas) {
        foreach ($filasValidas as $row) {
            $usuario = User::create([
                'name'     => $row['nombre'].' '.$row['apellido'],
                'email'    => $row['email'],
                'password' => Hash::make($row['ci']),
            ]);
            $usuario->assignRole('DOCENTE');

            $codigo = strtoupper(substr($row['apellido'], 0, 1))
                    . strtoupper(substr($row['nombre'], 0, 1))
                    . substr($row['ci'], 0, 4);

            $docente = Docente::create([
                'usuario_id'       => $usuario->id,
                'codigo'           => $codigo,
                'nombre'           => $row['nombre'],
                'apellido'         => $row['apellido'],
                'ci'               => $row['ci'],
                'fecha_nacimiento' => $row['fecha_nacimiento'],
                'telefono'         => $row['telefono'],
                'direccion'        => $row['direccion'],
                'foto'             => null,
            ]);

            Requisitos_Docente::create([
                'docente_codigo'   => $docente->codigo,
                'nombre_titulo'    => $row['nombre_titulo'],
                'nombre_maestria'  => $row['nombre_maestria'],
                'nombre_diplomado' => $row['nombre_diplomado'],
            ]);

            Docente_Materia::create([
                'codigo_docente' => $docente->codigo,
                'materia_id'     => $row['materia_id'],
                'estado'         => 'activo',
            ]);
        }
    });

    return back()->with('mensaje', '✅ Carga masiva de docentes realizada correctamente')->with('icono', 'success');
}
    


   

}
