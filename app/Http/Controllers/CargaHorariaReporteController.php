<?php

namespace App\Http\Controllers;

use App\Models\CargaHoraria;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Horario;
use App\Models\Turno;
use App\Models\Gestion;
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

        // Obtener docentes con carga horaria activa
        $docentes = Docente::whereHas('cargaHoraria', function ($q) use ($gestionActiva) {
            $q->where('gestion_id', $gestionActiva->id);
        })
        ->select('codigo', 'nombre', 'apellido')
        ->orderBy('nombre')
        ->get();

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
     */
    private function obtenerCargaGeneralDocentes(Gestion $gestionActiva)
    {
        return CargaHoraria::with([
            'docente',
            'materia',
            'grupo',
            'horario.turno',
            'aula'
        ])
        ->where('gestion_id', $gestionActiva->id)
        ->orderBy('docente_codigo')
        ->orderBy('grupo_id')
        ->get()
        ->groupBy('docente_codigo')
        ->map(function ($cargas) {
            return $cargas->map(function ($carga) {
                // Calcular minutos de la clase
                $inicio = Carbon::createFromFormat('H:i:s', $carga->horario->hora_inicio);
                $fin = Carbon::createFromFormat('H:i:s', $carga->horario->hora_fin);
                $minutos = $inicio->diffInMinutes($fin);
                $carga->duracion_minutos = $minutos;
                $carga->duracion_horas = $minutos / 60;
                return $carga;
            });
        });
    }

    /**
     * Construir tabla semanal con estructura de días y horarios
     * Basado en la lógica de GrupoController.showhorario()
     */
    private function construirTablaSemanal($docente_codigo, Gestion $gestionActiva)
    {
        // Obtener todas las cargas del docente con horarios reales
        $cargas = CargaHoraria::with(['grupo.turno', 'horario.turno', 'materia', 'aula'])
            ->where('docente_codigo', $docente_codigo)
            ->where('gestion_id', $gestionActiva->id)
            ->get();

        if ($cargas->isEmpty()) {
            return collect();
        }

        // Obtener todos los horarios únicos ordenados
        $horariosUnicos = $cargas->pluck('horario')
            ->unique('id')
            ->sortBy('hora_inicio')
            ->values();

        $tabla = [];
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        foreach ($horariosUnicos as $horario) {
            $fila = [];
            $fila['hora_inicio'] = $horario->hora_inicio;
            $fila['hora_fin'] = $horario->hora_fin;
            $fila['hora_display'] = substr($horario->hora_inicio, 0, 5) . ' - ' . substr($horario->hora_fin, 0, 5);

            // Para cada día, buscar TODAS las materias que tiene en ese horario
            foreach ($dias as $dia) {
                $cargasDelDiaYHora = $cargas->filter(function ($carga) use ($dia, $horario) {
                    $turnoNombre = $carga->grupo->turno->nombre ?? '';
                    $mismaHora = $carga->horario_id === $horario->id;
                    $mismoDia = strcasecmp($turnoNombre, $dia) === 0 || 
                               str_contains(strtolower($turnoNombre), strtolower($dia));
                    return $mismaHora && $mismoDia;
                })->values();

                if ($cargasDelDiaYHora->count() > 0) {
                    $fila[$dia] = $cargasDelDiaYHora->map(function ($carga) {
                        return [
                            'materia' => $carga->materia?->nombre,
                            'grupo' => $carga->grupo?->nombre,
                        ];
                    })->toArray();
                } else {
                    $fila[$dia] = null;
                }
            }

            $tabla[] = $fila;
        }

        return collect($tabla);
    }

    /**
     * Construir tabla de horario diaria (por horas)
     */
    private function construirTablaHoraria($docente_codigo, $fecha, Gestion $gestionActiva)
    {
        $diaSemana = Carbon::parse($fecha)->dayName;
        
        // Mapear nombre de día en inglés a español
        $diasMapeo = [
            'Monday' => ['Lunes', 'Monday'],
            'Tuesday' => ['Martes', 'Tuesday'],
            'Wednesday' => ['Miércoles', 'Wednesday'],
            'Thursday' => ['Jueves', 'Thursday'],
            'Friday' => ['Viernes', 'Friday'],
            'Saturday' => ['Sábado', 'Saturday'],
            'Sunday' => ['Domingo', 'Sunday'],
        ];

        $diasBusqueda = $diasMapeo[$diaSemana] ?? [$diaSemana];

        // Obtener cargas del docente para este día
        $cargas = CargaHoraria::with(['grupo.turno', 'horario.turno', 'materia', 'aula'])
            ->where('docente_codigo', $docente_codigo)
            ->where('gestion_id', $gestionActiva->id)
            ->get()
            ->filter(function ($carga) use ($diasBusqueda) {
                $turnoNombre = $carga->grupo->turno->nombre ?? '';
                return collect($diasBusqueda)->some(function ($dia) use ($turnoNombre) {
                    return strcasecmp($turnoNombre, $dia) === 0 || 
                           str_contains(strtolower($turnoNombre), strtolower($dia));
                });
            });

        // Obtener todos los horarios posibles del sistema ordenados
        $todosHorarios = Horario::orderBy('hora_inicio')->get();

        $tabla = [];
        foreach ($todosHorarios as $horario) {
            // Buscar TODAS las cargas en este horario
            $cargasEnHora = $cargas->filter(function ($item) use ($horario) {
                return $item->horario_id === $horario->id;
            })->values();

            $materias = $cargasEnHora->map(function ($carga) {
                return [
                    'materia' => $carga->materia?->nombre,
                    'grupo' => $carga->grupo?->nombre,
                ];
            })->toArray();

            $fila = [
                'hora' => $horario->hora_inicio,
                'hora_fin' => $horario->hora_fin,
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

