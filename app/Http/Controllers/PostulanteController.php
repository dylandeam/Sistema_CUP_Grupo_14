<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use App\Models\User;
use App\Models\Inscripcion;
use App\Models\Inscripcion_Carrera;
use App\Models\Requisitos_Postulante;
use App\Models\Turno;
use App\Services\BitacoraService;
use App\Services\GrupoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Pago;

class PostulanteController extends Controller
{
    public function index()
    {
        $postulantes = Postulante::with('user.roles')->get();
        return view('admin.postulantes.index', compact('postulantes'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.postulantes.create', compact('roles'));
    }

    public function showImportForm()
    {
        return view('admin.postulantes.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'          => 'required|string|max:255',
            'apellidos'       => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'ci'              => 'required|unique:postulantes,ci',
            'fecha_nacimiento'=> 'required|date',
            'sexo'            => 'required|in:M,F',
            'telefono'        => 'nullable|string|max:20',
            'direccion'       => 'required|string|max:255',
            'colegio'         => 'required|string|max:255',
            'ciudad'          => 'required|string|max:255',
            'rol'             => 'required',
            'foto'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'ci.unique'    => 'El CI ya está registrado en otro postulante.',
            'email.unique' => 'El correo ya está en uso.',
        ]);

        // Crear usuario
        $usuario = User::create([
            'name'     => $request->nombre . ' ' . $request->apellidos,
            'email'    => $request->email,
            'password' => Hash::make($request->ci),
        ]);
        $usuario->assignRole($request->rol);

        // Generar código
        $codigo = strtoupper(substr($request->apellidos,0,1)) .
                  strtoupper(substr($request->nombre,0,1)) .
                  substr($request->ci,0,4);

        // Foto
        $fotoPath = $request->hasFile('foto')
            ? $request->file('foto')->store('postulantes', 'public')
            : null;

        Postulante::create([
            'codigo'          => $codigo,
            'usuario_id'      => $usuario->id,
            'nombre'          => $request->nombre,
            'apellidos'       => $request->apellidos,
            'ci'              => $request->ci,
            'fecha_nacimiento'=> $request->fecha_nacimiento,
            'sexo'            => $request->sexo,
            'telefono'        => $request->telefono,
            'direccion'       => $request->direccion,
            'colegio'         => $request->colegio,
            'ciudad'          => $request->ciudad,
            'foto'            => $fotoPath,
        ]);

        BitacoraService::registrar('Creó postulante: ' . $request->nombre . ' ' . $request->apellidos);

        return redirect()->route('admin.postulantes.index')
            ->with('mensaje', 'Postulante registrado correctamente')
            ->with('icono', 'success');
    }

    public function show(Postulante $postulante)
    {
        $inscripciones = Inscripcion::with(['gestion','modalidad','pago'])
            ->where('postulante_codigo', $postulante->codigo)
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.postulantes.show', compact('postulante', 'inscripciones'));
    }

    public function edit(Postulante $postulante)
    {
        $roles = Role::all();
        return view('admin.postulantes.edit', compact('postulante','roles'));
    }

    public function update(Request $request, Postulante $postulante)
    {
        $request->validate([
            'nombre'          => 'required|string|max:255',
            'apellidos'       => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email,' . $postulante->usuario_id,
            'ci'              => 'required|unique:postulantes,ci,' . $postulante->codigo . ',codigo',
            'fecha_nacimiento'=> 'required|date',
            'sexo'            => 'required|in:M,F',
            'telefono'        => 'nullable|string|max:20',
            'direccion'       => 'required|string|max:255',
            'colegio'         => 'required|string|max:255',
            'ciudad'          => 'required|string|max:255',
            'rol'             => 'required',
            'foto'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'ci.unique'    => 'El CI ya está registrado en otro postulante.',
            'email.unique' => 'El correo ya está en uso.',
        ]);

        // Actualizar usuario
        $usuario = $postulante->user;
        $usuario->update([
            'name'  => $request->nombre . ' ' . $request->apellidos,
            'email' => $request->email,
        ]);
        $usuario->syncRoles([$request->rol]);

        // Guardar código antiguo para mantener relaciones si cambia
        $codigoAntiguo = $postulante->codigo;

        // Regenerar código
        $codigo = strtoupper(substr($request->apellidos,0,1)) .
                  strtoupper(substr($request->nombre,0,1)) .
                  substr($request->ci,0,4);

        // Foto
        $fotoPath = $postulante->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('postulantes', 'public');
        }

        $postulante->update([
            'codigo'          => $codigo,
            'nombre'          => $request->nombre,
            'apellidos'       => $request->apellidos,
            'ci'              => $request->ci,
            'fecha_nacimiento'=> $request->fecha_nacimiento,
            'sexo'            => $request->sexo,
            'telefono'        => $request->telefono,
            'direccion'       => $request->direccion,
            'colegio'         => $request->colegio,
            'ciudad'          => $request->ciudad,
            'foto'            => $fotoPath,
        ]);

        if ($codigo !== $codigoAntiguo) {
            Inscripcion::where('postulante_codigo', $codigoAntiguo)
                ->update(['postulante_codigo' => $codigo]);

            Requisitos_Postulante::where('postulante_codigo', $codigoAntiguo)
                ->update(['postulante_codigo' => $codigo]);
        }

        BitacoraService::registrar('Actualizó postulante: ' . $postulante->codigo);

        return redirect()->route('admin.postulantes.index')
            ->with('mensaje', 'Postulante actualizado correctamente')
            ->with('icono', 'success');
    }

    public function destroy(Postulante $postulante)
    {
        if ($postulante->foto && Storage::disk('public')->exists($postulante->foto)) {
            Storage::disk('public')->delete($postulante->foto);
        }

        if ($postulante->user) {
            $postulante->user->delete();
        }

        $postulante->delete();

        BitacoraService::registrar('Eliminó postulante: ' . $postulante->codigo);

        return redirect()->route('admin.postulantes.index')
            ->with('mensaje', 'Postulante eliminado correctamente')
            ->with('icono', 'success');
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
            'gestion'         => $getIndex(['gestion', 'gestión']),
            'modalidad'       => $getIndex(['modalidad']),
            'turno'           => $getIndex(['turno']),
            'nombre'          => $getIndex(['nombre']),
            'apellidos'       => $getIndex(['apellidos', 'apellido']),
            'ci'              => $getIndex(['ci']),
            'email'           => $getIndex(['email', 'correo']),
            'fecha_nacimiento'=> $getIndex(['fecha_nacimiento', 'fecha nacimiento']),
            'sexo'            => $getIndex(['sexo']),
            'telefono'        => $getIndex(['telefono', 'teléfono']),
            'direccion'       => $getIndex(['direccion', 'dirección']),
            'ciudad'          => $getIndex(['ciudad']),
            'colegio'         => $getIndex(['colegio']),
            'primera_opcion'  => $getIndex(['primera_opcion', 'primera opcion', '1ra opcion', '1ra_opcion']),
            'segunda_opcion'  => $getIndex(['segunda_opcion', 'segunda opcion', '2da opcion', '2da_opcion']),
        ];

        $convertToUtf8 = function ($value) {
            $value = (string) $value;
            if ($value === '') {
                return '';
            }

            if (mb_check_encoding($value, 'UTF-8')) {
                return $value;
            }

            $encoding = mb_detect_encoding($value, ['UTF-8', 'ISO-8859-1', 'Windows-1252', 'ASCII'], true);
            if ($encoding !== false) {
                $converted = @mb_convert_encoding($value, 'UTF-8', $encoding);
                if ($converted !== false && mb_check_encoding($converted, 'UTF-8')) {
                    return $converted;
                }
            }

            $converted = @iconv('CP1252', 'UTF-8//IGNORE', $value);
            if ($converted !== false && mb_check_encoding($converted, 'UTF-8')) {
                return $converted;
            }

            $converted = @iconv('UTF-8', 'UTF-8//IGNORE', $value);
            if ($converted !== false && mb_check_encoding($converted, 'UTF-8')) {
                return $converted;
            }

            return preg_replace('/[\x00-\x1F\x7F]/u', '', $value);
        };
        
        if ($indexes['turno'] === null) {
            return back()->with('mensaje', '❌ El archivo debe incluir la columna turno después de modalidad.')->with('icono', 'danger');
        }
        
        $errores     = [];
        $filasValidas = [];
        $ciUsados    = [];
        $emailUsados = [];

        // Obtener costo fijo desde el .env vía config/inscripcion.php
        $costoInscripcion = config('inscripcion.costo');
        
        $fechaHoy = now()->format('Y-m-d');
        
        foreach ($rows as $fila => $row) {
            if ($fila === 0) continue;
            
            $gestionRaw    = trim($convertToUtf8($row[$indexes['gestion']] ?? ''));
            $modalidadNombre = trim($convertToUtf8($row[$indexes['modalidad']] ?? ''));
            $nombre        = trim($convertToUtf8($row[$indexes['nombre']] ?? ''));
            $apellidos     = trim($convertToUtf8($row[$indexes['apellidos']] ?? ''));
            $ci            = preg_replace('/[^0-9]/', '', (string) ($row[$indexes['ci']] ?? ''));
            $email         = trim($convertToUtf8($row[$indexes['email']] ?? ''));
            $sexoRaw        = trim($convertToUtf8($row[$indexes['sexo']] ?? ''));
            $sexoNormalizado = strtoupper($sexoRaw);

            if (in_array($sexoNormalizado, ['FEMENINO', 'FEMALE', 'MUJER'], true)) {
                $sexo = 'F';
            } elseif (in_array($sexoNormalizado, ['MASCULINO', 'MALE', 'HOMBRE', 'VARON', 'VARÓN'], true)) {
                $sexo = 'M';
            } elseif (in_array($sexoNormalizado, ['F', 'M', 'O'], true)) {
                $sexo = $sexoNormalizado;
            } elseif ($sexoNormalizado !== '') {
                $sexo = strtoupper(substr($sexoNormalizado, 0, 1));
                if (!in_array($sexo, ['F', 'M', 'O'], true)) {
                    $sexo = 'O';
                }
            } else {
                $sexo = '';
            }

            $telefono      = preg_replace('/[^0-9]/', '', (string) ($row[$indexes['telefono']] ?? '')) ?: null;
                $direccion     = trim($convertToUtf8($row[$indexes['direccion']] ?? ''));
                $ciudad        = trim($convertToUtf8($row[$indexes['ciudad']] ?? ''));
                $colegio       = trim($convertToUtf8($row[$indexes['colegio']] ?? ''));
                $primeraOpcion = trim($convertToUtf8($row[$indexes['primera_opcion']] ?? ''));
                $segundaOpcion = trim($convertToUtf8($row[$indexes['segunda_opcion']] ?? ''));
                
                // Convertir fecha nacimiento (serial Excel o texto)
                $fechaRaw = $row[$indexes['fecha_nacimiento']] ?? '';
                if (! is_numeric($fechaRaw)) {
                    $fechaRaw = $convertToUtf8($fechaRaw);
                }
                if (is_numeric($fechaRaw) && $fechaRaw > 0) {
                    try {
                        $fechaNacimiento = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float)$fechaRaw)->format('Y-m-d');
                    } catch (\Exception $e) {
                        $fechaNacimiento = '';
                    }
                } else {
                    $fechaNacimiento = trim((string) $fechaRaw);
                }

                // Parsear gestión formato "semestre-año" ej: "1-2026" o fecha Excel serial date
                $gestionId = null;
                $semestre = null;
                $anio = null;

                $gestionRawTrimmed = trim((string) $gestionRaw);
                if (preg_match('/^(1|2)[\-\.\/\s](\d{4})$/', $gestionRawTrimmed, $matches)) {
                    $semestre = (int) $matches[1];
                    $anio = (int) $matches[2];
                } elseif (is_numeric($gestionRawTrimmed)) {
                    if (preg_match('/^[12]\d{3}$/', $gestionRawTrimmed)) {
                        $anio = (int) $gestionRawTrimmed;
                        $semestre = 1;
                    } else {
                        try {
                            $fechaGestion = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $gestionRawTrimmed);
                            $anio = (int) $fechaGestion->format('Y');
                            $mes = (int) $fechaGestion->format('n');
                            $semestre = $mes <= 6 ? 1 : 2;
                        } catch (\Exception $e) {
                            // ignore, fallback to missing gestion
                        }
                    }
                }

                if ($semestre !== null && $anio !== null) {
                    $gestion = \App\Models\Gestion::where('semestre', $semestre)
                        ->where('año', $anio)
                        ->first();

                    if ($gestion) {
                        $gestionId = $gestion->id;
                    }
                }

                if (!$gestionId) {
                    $errores[] = "❌ Error en fila ".($fila + 1).": La gestión '{$gestionRaw}' no existe o no tiene el formato correcto. Use semestre-año, ej: 1-2026, o una fecha Excel válida.";
                    continue;
                }
                        
                        // Buscar modalidad por nombre
                        $modalidad = \App\Models\Modalidad::whereRaw('LOWER(nombre) = ?', [mb_strtolower($modalidadNombre)])->first();
                        if (!$modalidad) {
                            $errores[] = "❌ Error en fila ".($fila + 1).": La modalidad '{$modalidadNombre}' no existe en el sistema.";
                            continue;
                        }

                        $turnoNombre = trim($convertToUtf8($row[$indexes['turno']] ?? ''));
                        $turno = null;
                        if ($turnoNombre !== '') {
                            if (ctype_digit($turnoNombre)) {
                                $turno = Turno::find($turnoNombre);
                            }
                            if (!$turno) {
                                $turno = Turno::whereRaw('LOWER(nombre) = ?', [mb_strtolower($turnoNombre)])->first();
                            }
                        }
                        if (!$turno) {
                            $errores[] = "❌ Error en fila ".($fila + 1).": El turno '{$turnoNombre}' no existe en el sistema.";
                            continue;
                        }
                        
                        // Buscar carreras
                        $carreraPrimera = \App\Models\Carrera::whereRaw('LOWER(nombre) = ?', [mb_strtolower($primeraOpcion)])->first();
                        if (!$carreraPrimera) {
                            $errores[] = "❌ Error en fila ".($fila + 1).": La carrera de primera opción '{$primeraOpcion}' no existe en el sistema.";
                            continue;
                        }
                        
                        $carreraSegunda = \App\Models\Carrera::whereRaw('LOWER(nombre) = ?', [mb_strtolower($segundaOpcion)])->first();
                        if (!$carreraSegunda) {
                            $errores[] = "❌ Error en fila ".($fila + 1).": La carrera de segunda opción '{$segundaOpcion}' no existe en el sistema.";
                            continue;
                        }
                        
                        $filaData = [
                            'gestion_id'              => $gestionId,
                            'modalidad_id'            => $modalidad->id,
                            'turno_id'                => $turno->id,
                            'nombre'                  => $nombre,
                            'apellidos'               => $apellidos,
                            'ci'                      => $ci,
                            'email'                   => $email,
                            'fecha_nacimiento'        => $fechaNacimiento,
                            'sexo'                    => $sexo,
                            'telefono'                => $telefono,
                            'direccion'               => $direccion,
                            'ciudad'                  => $ciudad,
                            'colegio'                 => $colegio,
                            'carrera_primera_opcion_id' => $carreraPrimera->id,
                            'carrera_segunda_opcion_id' => $carreraSegunda->id,
                        ];
                        
                        // Validar duplicados en el archivo
                        if (in_array($ci, $ciUsados)) {
                            $errores[] = "❌ Error en fila ".($fila + 1).": El CI {$ci} está duplicado en el archivo.";
                            continue;
                        }
                        
                        if (in_array($email, $emailUsados)) {
                            $errores[] = "❌ Error en fila ".($fila + 1).": El Email {$email} está duplicado en el archivo.";
                            continue;
                        }
                        
                        $ciUsados[]    = $ci;
                        $emailUsados[] = $email;
                        
                        $validator = Validator::make($filaData, [
                            'gestion_id'       => 'required|exists:gestions,id',
                            'modalidad_id'     => 'required|exists:modalidades,id',
                            'turno_id'         => 'required|exists:turnos,id',
                            'nombre'          => 'required|string|max:255',
                            'apellidos'       => 'required|string|max:255',
                            'ci'              => 'required|unique:postulantes,ci',
                            'email'           => 'required|email|unique:users,email',
                            'fecha_nacimiento'=> 'required|date',
                            'sexo'            => 'required|in:M,F,O',
                            'telefono'        => 'nullable|string|max:20',
                            'direccion'       => 'nullable|string|max:255',
                            'ciudad'          => 'required|string|max:255',
                            'colegio'         => 'required|string|max:255',
                        ], [
                            'nombre.required'           => 'El nombre es obligatorio.',
                            'apellidos.required'        => 'Los apellidos son obligatorios.',
                            'ci.required'               => 'El CI es obligatorio.',
                            'ci.unique'                 => 'El CI ya está registrado en el sistema.',
                            'email.required'            => 'El email es obligatorio.',
                            'email.email'               => 'El email no tiene un formato válido.',
                            'email.unique'              => 'El email ya está registrado en el sistema.',
                            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
                            'fecha_nacimiento.date'     => 'La fecha de nacimiento no tiene un formato válido (use YYYY-MM-DD).',
                            'sexo.required'             => 'El sexo es obligatorio.',
                            'sexo.in'                   => 'El sexo debe ser M, F u O.',
                            'ciudad.required'           => 'La ciudad de origen es obligatoria.',
                            'colegio.required'          => 'El colegio de procedencia es obligatorio.',
                        ]);
                        
                        if ($validator->fails()) {
                            $errores[] = "❌ Error en fila ".($fila + 1).": ".implode(' | ', $validator->errors()->all());
                            continue;
                        }
                        
                        $filasValidas[] = $filaData;
                    }
                    
                    if (!empty($errores)) {
                        return back()->with('mensaje', implode('<br>', $errores))->with('icono', 'danger');
                    }

                    $existingCodigos = Postulante::pluck('codigo')->toArray();
                    $codigosGenerados = [];
                    
                    DB::transaction(function () use ($filasValidas, $costoInscripcion, $fechaHoy, &$existingCodigos, &$codigosGenerados) {
                        foreach ($filasValidas as $row) {
                            // Crear usuario
                            $usuario = User::create([
                                'name'     => $row['nombre'].' '.$row['apellidos'],
                                'email'    => $row['email'],
                                'password' => Hash::make($row['ci']),
                            ]);
                            $usuario->assignRole('POSTULANTE');

                            // Generar código único del postulante
                            $baseCodigo = strtoupper(substr($row['apellidos'], 0, 1))
                                . strtoupper(substr($row['nombre'], 0, 1))
                                . substr(preg_replace('/\D/', '', $row['ci']), 0, 4);
                            if ($baseCodigo === '') {
                                $baseCodigo = 'P' . substr(md5(uniqid('', true)), 0, 4);
                            }
                            $codigo = $baseCodigo;

                            while (in_array($codigo, $existingCodigos, true) || in_array($codigo, $codigosGenerados, true)) {
                                $codigo = $baseCodigo . '_' . substr(md5(uniqid('', true)), 0, 6);
                            }
                            
                            $codigosGenerados[] = $codigo;
                            $existingCodigos[] = $codigo;

                            // Crear postulante
                            $postulante = Postulante::create([
                                'codigo'          => $codigo,
                                'usuario_id'      => $usuario->id,
                                'nombre'          => $row['nombre'],
                                'apellidos'       => $row['apellidos'],
                                'ci'              => $row['ci'],
                                'fecha_nacimiento'=> $row['fecha_nacimiento'],
                                'sexo'            => $row['sexo'],
                                'telefono'        => $row['telefono'],
                                'direccion'       => $row['direccion'],
                                'ciudad'          => $row['ciudad'],
                                'colegio'         => $row['colegio'],
                                'foto'            => null,
                            ]);
                            
                            // Crear requisitos todos en true con el código del postulante
                            Requisitos_Postulante::create([
                                'postulante_codigo'      => $postulante->codigo,
                                'fotocopia_ci'           => true,
                                'certificado_nacimiento' => true,
                                'titulo_bachiller'       => true,
                                'libreta_colegio'        => true,
                            ]);
                            
                            // Crear inscripción primer
                            $inscripcion = Inscripcion::create([
                                'postulante_codigo'              => $postulante->codigo,
                                'gestion_id'                     => $row['gestion_id'],
                                'modalidad_id'                   => $row['modalidad_id'],
                                'turno_id'                       => $row['turno_id'],
                                'estado'                         => 'INSCRITO',
                                'fecha_insc'                     => $fechaHoy,
                                'costo'                          => $costoInscripcion,
                            ]);

                            GrupoService::generarGrupoParaInscripcion($inscripcion);
                            
                            // Crear las dos opciones de carrera para esta inscripción
                            Inscripcion_Carrera::create([
                                'orden_pref'    => 1,
                                'inscripcion_id'=> $inscripcion->id,
                                'carrera_id'    => $row['carrera_primera_opcion_id'],
                            ]);
                            Inscripcion_Carrera::create([
                                'orden_pref'    => 2,
                                'inscripcion_id'=> $inscripcion->id,
                                'carrera_id'    => $row['carrera_segunda_opcion_id'],
                            ]);
                            // Crear pago con el campo correcto y la relación a inscripción
                            $pago = Pago::create([
                                'inscripcion_id' => $inscripcion->id,
                                'monto'          => $costoInscripcion,
                                'fecha'          => $fechaHoy,
                                'estado'         => 'CONFIRMADO',
                                'comprobante'    => 'Extracto por PayPal',
                            ]);
                        }
                    });
                    return back()->with('mensaje', '✅ Carga masiva de postulantes realizada correctamente')->with('icono', 'success');
                }
}
