<?php

namespace App\Http\Controllers;

use App\Models\CargaHoraria;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Horario;
use App\Models\Turno;
use App\Models\Gestion;
use App\Models\Materia;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CargaHorariaReporteController extends Controller
{
    /**
     * Mostrar reporte de carga horaria con opciones de vista
     */
    public function index(Request $request)
    {
        $tipoVista = $request->get('tipo', 'general'); // general, semanal, diaria
        $docente_codigo = $request->get('docente');
        $fecha = $request->get('fecha', now()->format('Y-m-d'));
        
        // Obtener gestión activa
        $gestionActiva = Gestion::where('estado', 'Activa')->first();
        if (!$gestionActiva) {
            return view('admin.reportes.carga_horaria', [
                'tipoVista' => $tipoVista,
                'docente_codigo' => $docente_codigo,
                'fecha' => $fecha,
                'docentes' => collect(),
                'cargasHorarias' => collect(),
                'turnosPorDia' => collect(),
                'horariosOrdenados' => collect(),
                'gestionActiva' => null,
                'error' => 'No hay gestiones activas',
                'horasTrabajadas' => 0,
            ]);
        }

        // Verificar si hay docentes registrados en el sistema
        $totalDocentes = Docente::count();
        if ($totalDocentes === 0) {
            return view('admin.reportes.carga_horaria', [
                'tipoVista' => $tipoVista,
                'docente_codigo' => $docente_codigo,
                'fecha' => $fecha,
                'docentes' => collect(),
                'cargasHorarias' => collect(),
                'turnosPorDia' => collect(),
                'horariosOrdenados' => collect(),
                'gestionActiva' => $gestionActiva,
                'error' => 'No hay docentes registrados en el sistema. Por favor, registre docentes para poder ver su carga horaria.',
                'horasTrabajadas' => 0,
            ]);
        }

        // Obtener docentes con carga horaria activa
        $docentes = Docente::whereHas('cargaHoraria', function ($q) use ($gestionActiva) {
            $q->where('gestion_id', $gestionActiva->id);
        })
        ->select('codigo', 'nombre', 'apellido')
        ->orderBy('nombre')
        ->get();

        // Verificar si hay docentes con carga horaria en la gestión activa
        if ($docentes->isEmpty()) {
            return view('admin.reportes.carga_horaria', [
                'tipoVista' => $tipoVista,
                'docente_codigo' => $docente_codigo,
                'fecha' => $fecha,
                'docentes' => collect(),
                'cargasHorarias' => collect(),
                'turnosPorDia' => collect(),
                'horariosOrdenados' => collect(),
                'gestionActiva' => $gestionActiva,
                'error' => 'No hay docentes con carga horaria asignada en la gestión activa. Por favor, asigne carga horaria a los docentes para poder ver este reporte.',
                'horasTrabajadas' => 0,
            ]);
        }

        $cargasHorarias = collect();
        $turnosPorDia = collect();
        $horariosOrdenados = collect();
        $error = null;
        $horasTrabajadas = 0;

        if ($tipoVista === 'general') {
            $cargasHorarias = $this->obtenerCargaGeneralDocentes($gestionActiva);
        } elseif ($tipoVista === 'semanal') {
            if (!$docente_codigo) {
                $error = 'Debe seleccionar un docente para vista semanal';
            } else {
                $turnosPorDia = $this->construirTablaSemanal($docente_codigo, $gestionActiva);
                $horasTrabajadas = $this->calcularHorasTrabajadas($docente_codigo, $gestionActiva);
            }
        } elseif ($tipoVista === 'diaria') {
            if (!$docente_codigo) {
                $error = 'Debe seleccionar un docente para vista diaria';
            } else {
                $horariosOrdenados = $this->construirTablaHoraria($docente_codigo, $fecha, $gestionActiva);
                $horasTrabajadas = $this->calcularHorasTrabajadas($docente_codigo, $gestionActiva);
            }
        }

        return view('admin.reportes.carga_horaria', compact(
            'tipoVista',
            'docente_codigo',
            'fecha',
            'docentes',
            'cargasHorarias',
            'turnosPorDia',
            'horariosOrdenados',
            'gestionActiva',
            'error',
            'horasTrabajadas'
        ));
    }

    /**
     * Obtener carga horaria general de todos los docentes
     * Agrupar por docente y calcular total de horas trabajadas
     */
    private function obtenerCargaGeneralDocentes(Gestion $gestionActiva)
    {
        // Obtener todas las cargas agrupadas por docente
        $cargas = CargaHoraria::with([
            'docente',
            'materia',
            'grupo',
            'horario'
        ])
        ->where('gestion_id', $gestionActiva->id)
        ->orderBy('docente_codigo')
        ->get()
        ->groupBy('docente_codigo');

        // Transformar para calcular horas totales por docente
        return $cargas->map(function ($cargasDocente) {
            $horasTotales = 0;
            
            foreach ($cargasDocente as $carga) {
                if ($carga->horario) {
                    $inicio = Carbon::createFromFormat('H:i:s', $carga->horario->hora_inicio);
                    $fin = Carbon::createFromFormat('H:i:s', $carga->horario->hora_fin);
                    $minutos = $inicio->diffInMinutes($fin);
                    // Solo contar si no es receso (>= 20 minutos)
                    if ($minutos >= 20) {
                        $horasTotales += $minutos / 60;
                    }
                }
            }

            // Agregar info de horas totales a cada carga para la vista
            return $cargasDocente->map(function ($carga) use ($horasTotales) {
                $carga->horas_trabajadas_total = round($horasTotales, 2);
                return $carga;
            });
        });
    }

    /**
     * Construir tabla semanal con estructura de días y horarios
     * Usa el patrón de rotación del grupo para determinar en qué días se dicta cada materia
     */
    private function construirTablaSemanal($docente_codigo, Gestion $gestionActiva)
    {
        // Obtener todas las cargas del docente con sus relaciones
        $cargas = CargaHoraria::with(['grupo', 'horario', 'materia'])
            ->where('docente_codigo', $docente_codigo)
            ->where('gestion_id', $gestionActiva->id)
            ->get();

        if ($cargas->isEmpty()) {
            return collect();
        }

        // Patrón de rotación de materias por día (mismo que GrupoController)
        $patronesPorDia = [
            'Lunes' => [0, 1, 3, 2],
            'Martes' => [1, 2, 3, 0],
            'Miércoles' => [2, 3, 1, 0],
            'Jueves' => [3, 1, 0, 2],
            'Viernes' => [0, 2, 1, 3],
        ];

        // Obtener todos los horarios de los turnos donde el docente dicta clases
        $turnoIds = $cargas->pluck('grupo.id_turno')->unique();
        $todosHorarios = Horario::whereIn('turno_id', $turnoIds)
            ->orderBy('turno_id')
            ->orderBy('hora_inicio')
            ->get();

        $dias = array_keys($patronesPorDia);
        $tabla = [];

        // Para cada horario, construir fila
        foreach ($todosHorarios as $horario) {
            $fila = [
                'hora_display' => substr($horario->hora_inicio, 0, 5) . ' - ' . substr($horario->hora_fin, 0, 5),
            ];

            // Para cada día de la semana
            foreach ($dias as $dia) {
                $cargasDelDia = [];

                // Buscar todas las cargas del docente en este horario y día
                foreach ($cargas as $carga) {
                    // Verificar si esta carga corresponde a este horario
                    if ($carga->horario_id !== $horario->id) {
                        continue;
                    }

                    // Obtener el grupo y sus materias
                    $grupo = $carga->grupo;
                    
                    // Obtener las 4 materias de THIS grupo (no del sistema)
                    $materiasGrupo = CargaHoraria::where('grupo_id', $grupo->id)
                        ->where('gestion_id', $gestionActiva->id)
                        ->with('materia')
                        ->get()
                        ->pluck('materia')
                        ->unique('id')
                        ->values()
                        ->sortBy('nombre')
                        ->take(4)
                        ->values();

                    // Encontrar el índice de esta materia en el grupo
                    $indiceMateria = -1;
                    foreach ($materiasGrupo as $idx => $mat) {
                        if ($mat->id === $carga->materia_id) {
                            $indiceMateria = $idx;
                            break;
                        }
                    }

                    // Obtener los horarios del turno del grupo en orden
                    $horariosDelTurno = Horario::where('turno_id', $grupo->id_turno)
                        ->orderBy('hora_inicio')
                        ->get();

                    // Encontrar el período (posición) de este horario, excluyendo reces
                    $periodoClase = -1;
                    $periodoActual = 0;
                    foreach ($horariosDelTurno as $h) {
                        // Verificar si es receso
                        $hInicio = Carbon::createFromFormat('H:i:s', $h->hora_inicio);
                        $hFin = Carbon::createFromFormat('H:i:s', $h->hora_fin);
                        $hDuracion = $hInicio->diffInMinutes($hFin);
                        
                        if ($hDuracion >= 20) {
                            // Es clase, no receso
                            if ($h->id === $horario->id) {
                                $periodoClase = $periodoActual;
                                break;
                            }
                            $periodoActual++;
                        }
                    }

                    // Verificar si la duración es receso (< 20 minutos)
                    $inicio = Carbon::createFromFormat('H:i:s', $horario->hora_inicio);
                    $fin = Carbon::createFromFormat('H:i:s', $horario->hora_fin);
                    $duracion = $inicio->diffInMinutes($fin);
                    $esReceso = $duracion < 20;

                    // Aplicar patrón si válido
                    if (!$esReceso && $indiceMateria >= 0 && $periodoClase >= 0) {
                        $patronDelDia = $patronesPorDia[$dia];
                        $indiceEsperado = $periodoClase % 4;
                        $materiaEsperada = $patronDelDia[$indiceEsperado] ?? null;

                        if ($materiaEsperada === $indiceMateria) {
                            $cargasDelDia[] = [
                                'materia' => $carga->materia?->nombre,
                                'grupo' => $carga->grupo?->nombre,
                            ];
                        }
                    }
                }

                $fila[$dia] = count($cargasDelDia) > 0 ? $cargasDelDia : null;
            }

            $tabla[] = $fila;
        }

        return collect($tabla);
    }

    /**
     * Construir tabla de horario diaria (por horas)
     * Filtra solo las cargas que corresponden al día especificado, usando el patrón del grupo
     */
    private function construirTablaHoraria($docente_codigo, $fecha, Gestion $gestionActiva)
    {
        // Obtener el día de la semana
        $diaSemana = Carbon::parse($fecha)->dayName;
        
        // Mapear nombre de día en inglés a español
        $diasMapeo = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo',
        ];
        
        $diaEspanol = $diasMapeo[$diaSemana] ?? null;

        // Patrón de rotación (mismo que GrupoController)
        $patronesPorDia = [
            'Lunes' => [0, 1, 3, 2],
            'Martes' => [1, 2, 3, 0],
            'Miércoles' => [2, 3, 1, 0],
            'Jueves' => [3, 1, 0, 2],
            'Viernes' => [0, 2, 1, 3],
        ];

        // Obtener todas las cargas del docente
        $cargas = CargaHoraria::with(['horario', 'materia', 'grupo'])
            ->where('docente_codigo', $docente_codigo)
            ->where('gestion_id', $gestionActiva->id)
            ->get();

        // Obtener todos los horarios de los turnos donde el docente dicta clases
        $turnoIds = $cargas->pluck('grupo.id_turno')->unique();
        $todosHorarios = Horario::whereIn('turno_id', $turnoIds)
            ->orderBy('turno_id')
            ->orderBy('hora_inicio')
            ->get();

        $tabla = [];
        foreach ($todosHorarios as $horario) {
            $materias = [];

            // Buscar todas las cargas del docente en este horario
            foreach ($cargas as $carga) {
                if ($carga->horario_id !== $horario->id) {
                    continue;
                }

                // Obtener el grupo y sus materias
                $grupo = $carga->grupo;
                
                // Obtener las 4 materias de THIS grupo
                $materiasGrupo = CargaHoraria::where('grupo_id', $grupo->id)
                    ->where('gestion_id', $gestionActiva->id)
                    ->with('materia')
                    ->get()
                    ->pluck('materia')
                    ->unique('id')
                    ->values()
                    ->sortBy('nombre')
                    ->take(4)
                    ->values();

                // Encontrar el índice de esta materia
                $indiceMateria = -1;
                foreach ($materiasGrupo as $idx => $mat) {
                    if ($mat->id === $carga->materia_id) {
                        $indiceMateria = $idx;
                        break;
                    }
                }

                // Obtener los horarios del turno del grupo
                $horariosDelTurno = Horario::where('turno_id', $grupo->id_turno)
                    ->orderBy('hora_inicio')
                    ->get();

                // Encontrar el período (posición) de este horario, excluyendo reces
                $periodoClase = -1;
                $periodoActual = 0;
                foreach ($horariosDelTurno as $h) {
                    // Verificar si es receso
                    $hInicio = Carbon::createFromFormat('H:i:s', $h->hora_inicio);
                    $hFin = Carbon::createFromFormat('H:i:s', $h->hora_fin);
                    $hDuracion = $hInicio->diffInMinutes($hFin);
                    
                    if ($hDuracion >= 20) {
                        // Es clase, no receso
                        if ($h->id === $horario->id) {
                            $periodoClase = $periodoActual;
                            break;
                        }
                        $periodoActual++;
                    }
                }

                // Verificar si es receso
                $inicio = Carbon::createFromFormat('H:i:s', $horario->hora_inicio);
                $fin = Carbon::createFromFormat('H:i:s', $horario->hora_fin);
                $duracion = $inicio->diffInMinutes($fin);
                $esReceso = $duracion < 20;

                // Si NO es receso y coincide con el día del patrón
                if (!$esReceso && $indiceMateria >= 0 && $periodoClase >= 0 && $diaEspanol) {
                    $patronDelDia = $patronesPorDia[$diaEspanol];
                    $indiceEsperado = $periodoClase % 4;
                    $materiaEsperada = $patronDelDia[$indiceEsperado] ?? null;

                    if ($materiaEsperada === $indiceMateria) {
                        $materias[] = [
                            'materia' => $carga->materia?->nombre,
                            'grupo' => $carga->grupo?->nombre,
                        ];
                    }
                }
            }

            $fila = [
                'hora_display' => substr($horario->hora_inicio, 0, 5) . ' - ' . substr($horario->hora_fin, 0, 5),
                'materias' => $materias,
            ];
            
            $tabla[] = $fila;
        }

        return collect($tabla);
    }

    /**
     * Calcular total de horas trabajadas
     */
    private function calcularHorasTrabajadas($docente_codigo, Gestion $gestionActiva)
    {
        $cargas = CargaHoraria::with('horario')
            ->where('docente_codigo', $docente_codigo)
            ->where('gestion_id', $gestionActiva->id)
            ->get();

        $totalMinutos = 0;
        foreach ($cargas as $carga) {
            if ($carga->horario) {
                $inicio = Carbon::createFromFormat('H:i:s', $carga->horario->hora_inicio);
                $fin = Carbon::createFromFormat('H:i:s', $carga->horario->hora_fin);
                $minutos = $inicio->diffInMinutes($fin);
                // Solo contar si no es receso (>= 20 minutos)
                if ($minutos >= 20) {
                    $totalMinutos += $minutos;
                }
            }
        }

        return round($totalMinutos / 60, 2); // Convertir a horas con 2 decimales
    }

    /**
     * Validar si hay choque de horarios para un docente
     * @return array Con 'existe' => bool y 'mensaje' => string
     */
    public static function validarChoqueHorario($docente_codigo, $horario_id, $grupo_id, $gestion_id, $excluir_id = null)
    {
        $horarioActual = Horario::find($horario_id);
        if (!$horarioActual) {
            return ['existe' => false, 'mensaje' => ''];
        }

        // Obtener todas las cargas del docente en esta gestión
        $cargas = CargaHoraria::where('docente_codigo', $docente_codigo)
            ->where('gestion_id', $gestion_id)
            ->with(['grupo', 'horario.turno'])
            ->get();

        if ($excluir_id) {
            $cargas = $cargas->where('id', '!=', $excluir_id);
        }

        foreach ($cargas as $carga) {
            // Si es el mismo grupo, no hay conflicto
            if ($carga->grupo_id === $grupo_id) {
                continue;
            }

            // Si el docente tiene la misma hora de inicio con otro grupo
            if ($carga->horario->hora_inicio === $horarioActual->hora_inicio) {
                $grupoActual = Grupo::find($grupo_id);
                $grupoExistente = $carga->grupo;
                
                return [
                    'existe' => true,
                    'mensaje' => "El docente ya tiene clase a las {$horarioActual->hora_inicio} en el grupo {$grupoExistente?->nombre}. No puede asignarse al grupo {$grupoActual?->nombre}."
                ];
            }

            // Validar superposición de horarios
            $horaActualInicio = Carbon::createFromFormat('H:i:s', $horarioActual->hora_inicio);
            $horaActualFin = Carbon::createFromFormat('H:i:s', $horarioActual->hora_fin);
            
            $horaExistenteInicio = Carbon::createFromFormat('H:i:s', $carga->horario->hora_inicio);
            $horaExistenteFin = Carbon::createFromFormat('H:i:s', $carga->horario->hora_fin);

            // Hay superposición si: inicio < finExistente AND fin > inicioExistente
            if ($horaActualInicio < $horaExistenteFin && $horaActualFin > $horaExistenteInicio) {
                return [
                    'existe' => true,
                    'mensaje' => "El docente tiene conflicto de horario: sus clases se superponen entre las {$carga->horario->hora_inicio} y {$carga->horario->hora_fin}."
                ];
            }
        }

        return ['existe' => false, 'mensaje' => ''];
    }

    /**
     * Exportar carga horaria a Excel
     */
    public function exportarExcel(Request $request)
    {
        $tipoVista = $request->get('tipo', 'general');
        $docente_codigo = $request->get('docente');
        $fecha = $request->get('fecha', now()->format('Y-m-d'));

        $gestionActiva = Gestion::where('estado', 'Activa')->first();
        if (!$gestionActiva) {
            return redirect()->back()->with('error', 'No hay gestiones activas');
        }

        if ($tipoVista === 'general') {
            $cargasHorarias = $this->obtenerCargaGeneralDocentes($gestionActiva);
            return $this->exportarExcelGeneral($cargasHorarias, $docente_codigo);
        } elseif ($tipoVista === 'diaria') {
            $horariosOrdenados = $this->construirTablaHoraria($docente_codigo, $fecha, $gestionActiva);
            return $this->exportarExcelDiaria($horariosOrdenados, $docente_codigo, $fecha);
        }

        return redirect()->back()->with('error', 'Tipo de exportación no válido');
    }

    /**
     * Exportar vista general a Excel
     */
    private function exportarExcelGeneral($cargasHorarias, $docente_codigo = null)
    {
        // TODO: Implementar con PhpSpreadsheet
        return response()->json(['message' => 'Exportación en desarrollo']);
    }

    /**
     * Exportar vista diaria a Excel
     */
    private function exportarExcelDiaria($horariosOrdenados, $docente_codigo, $fecha)
    {
        // TODO: Implementar con PhpSpreadsheet
        return response()->json(['message' => 'Exportación en desarrollo']);
    }
}

