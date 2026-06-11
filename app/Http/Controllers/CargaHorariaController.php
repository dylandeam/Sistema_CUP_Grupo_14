<?php

namespace App\Http\Controllers;

use App\Models\CargaHoraria;
use App\Models\Docente;
use App\Models\Materia;
use App\Models\Grupo;
use App\Models\Horario;
use App\Models\Aula;
use App\Models\Gestion;
use App\Services\GrupoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CargaHorariaController extends Controller
{
    // Listar todas las cargas horarias agrupadas por docente
    public function index()
    {
        // Validar que existan docentes registrados
        $docentesCount = Docente::count();
        if ($docentesCount === 0) {
            return redirect()->route('admin.docentes.index')
                ->with('error', 'Debes registrar al menos un docente antes de ver la carga horaria.')
                ->with('icono', 'warning');
        }
        
        $this->generarCargaHorariaAutomatica();

        // Obtener docentes únicos que tienen carga horaria con gestión activa
        $gestionActiva = Gestion::where('estado', 'Activa')->first();
        
        if (!$gestionActiva) {
            $docentes = collect();
            $activeGestionMessage = 'No hay gestiones activas.';
        } else {
            $docentes = Docente::whereHas('cargaHoraria', function ($query) use ($gestionActiva) {
                $query->where('gestion_id', $gestionActiva->id);
            })->with(['cargaHoraria' => function ($query) use ($gestionActiva) {
                $query->where('gestion_id', $gestionActiva->id)
                      ->with(['materia', 'grupo', 'horario', 'aula', 'gestion']);
            }])->orderBy('nombre')->get();
            
            $activeGestionMessage = null;
        }

        return view('admin.carga_horarias.index', compact('docentes', 'activeGestionMessage', 'gestionActiva'));
    }

    // Mostrar detalle de carga horaria
    public function show(CargaHoraria $cargaHoraria)
    {
        return view('admin.carga_horarias.show', compact('cargaHoraria'));
    }

    // Mostrar carga horaria de un docente específico
    public function showDocente(Docente $docente)
    {
        // Validar que existan docentes registrados
        $docentesCount = Docente::count();
        if ($docentesCount === 0) {
            return redirect()->route('admin.docentes.index')
                ->with('error', 'No hay docentes registrados.')
                ->with('icono', 'warning');
        }
        
        $gestionActiva = Gestion::where('estado', 'Activa')->first();
        
        if (!$gestionActiva) {
            $cargaHoraria = collect();
            $activeGestionMessage = 'No hay gestiones activas.';
        } else {
            $cargaHoraria = CargaHoraria::with(['materia', 'grupo', 'horario', 'aula', 'gestion'])
                ->where('docente_codigo', $docente->codigo)
                ->where('gestion_id', $gestionActiva->id)
                ->get();
            
            $activeGestionMessage = null;
        }

        // Calcular horas trabajadas
        $horasTrabajadas = 0;
        foreach ($cargaHoraria as $carga) {
            if ($carga->horario) {
                $inicio = \Carbon\Carbon::createFromFormat('H:i:s', $carga->horario->hora_inicio);
                $fin = \Carbon\Carbon::createFromFormat('H:i:s', $carga->horario->hora_fin);
                $minutos = $inicio->diffInMinutes($fin);
                
                // Si no es receso (>= 20 minutos), contar como horas trabajadas
                if ($minutos >= 20) {
                    $horasTrabajadas += ($minutos / 60);
                }
            }
        }

        // Horas requeridas: 4 horas * 5 días a la semana (suposición estándar)
        $horasRequeridas = 20;

        return view('admin.carga_horarias.show_docente', compact('docente', 'cargaHoraria', 'horasTrabajadas', 'horasRequeridas', 'activeGestionMessage', 'gestionActiva'));
    }

    private function generarCargaHorariaAutomatica(): void
    {
        $gestionActiva = Gestion::where('estado', 'Activa')->orderBy('semestre')->first();
        if ($gestionActiva) {
            GrupoService::generarGruposDesdeInscripcionesExistentes($gestionActiva->id);
        }

        $grupos = Grupo::whereHas('gestion', function ($query) {
                $query->where('estado', 'Activa');
            })
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('carga_horarias')
                    ->whereColumn('carga_horarias.grupo_id', 'grupos.id');
            })
            ->get();

        if ($grupos->isEmpty()) {
            return;
        }

        $materias = $this->obtenerMateriasNecesarias();
        if (count($materias) < 4) {
            return;
        }

        $horariosPorTurno = Horario::all()->groupBy('turno_id');

        $docenteContador = CargaHoraria::select('docente_codigo', DB::raw('count(distinct grupo_id) as grupos'))
            ->groupBy('docente_codigo')
            ->pluck('grupos', 'docente_codigo')
            ->toArray();

        DB::transaction(function () use ($grupos, $materias, $horariosPorTurno, &$docenteContador, $gestionActiva) {
            foreach ($grupos as $grupo) {
                $asignadosEnGrupo = [];
                $horariosDelTurno = $horariosPorTurno->get($grupo->id_turno) ?? collect();

                if ($horariosDelTurno->isEmpty()) {
                    continue;
                }

                foreach ($materias as $materia) {
                    $docente = $this->seleccionarDocenteDisponible($materia, $docenteContador, $asignadosEnGrupo);
                    if (! $docente) {
                        continue 2;
                    }

                    // Buscar un horario disponible sin choque
                    $horarioAsignado = null;
                    foreach ($horariosDelTurno as $horario) {
                        $validacion = CargaHorariaReporteController::validarChoqueHorario(
                            $docente->codigo,
                            $horario->id,
                            $grupo->id,
                            $grupo->id_gestion
                        );

                        if (!$validacion['existe']) {
                            $horarioAsignado = $horario;
                            break;
                        }
                    }

                    // Si no hay horario disponible sin choque, usar el primero
                    if (!$horarioAsignado) {
                        $horarioAsignado = $horariosDelTurno->first();
                    }

                    CargaHoraria::create([
                        'docente_codigo' => $docente->codigo,
                        'materia_id'     => $materia->id,
                        'grupo_id'       => $grupo->id,
                        'horario_id'     => $horarioAsignado->id,
                        'aula_id'        => $grupo->id_aula,
                        'gestion_id'     => $grupo->id_gestion,
                    ]);

                    $asignadosEnGrupo[] = $docente->codigo;
                    if (! isset($docenteContador[$docente->codigo])) {
                        $docenteContador[$docente->codigo] = 0;
                    }
                    $docenteContador[$docente->codigo]++;
                }
            }
        });
    }

    private function obtenerMateriasNecesarias(): array
    {
        $asignaturas = [
            ['matematica', 'matemática', 'matematicas', 'matemáticas'],
            ['fisica', 'física'],
            ['ingles', 'inglés'],
            ['computacion', 'computación'],
        ];

        $materias = [];
        foreach ($asignaturas as $aliases) {
            $materia = Materia::where(function ($query) use ($aliases) {
                foreach ($aliases as $alias) {
                    $query->orWhereRaw('LOWER(nombre) LIKE ?', ['%' . mb_strtolower($alias) . '%']);
                }
            })->first();

            if (! $materia) {
                return [];
            }

            $materias[] = $materia;
        }

        return $materias;
    }

    private function seleccionarDocenteDisponible(Materia $materia, array $contadores, array $excluidos)
    {
        $docentes = $materia->docentes()->get();
        if ($docentes->isEmpty()) {
            return null;
        }

        $disponibles = $docentes->filter(function ($docente) use ($contadores, $excluidos) {
            $asignaciones = $contadores[$docente->codigo] ?? 0;
            return $asignaciones < 4 && ! in_array($docente->codigo, $excluidos, true);
        });

        if ($disponibles->isEmpty()) {
            return null;
        }

        return $disponibles->sortBy(function ($docente) use ($contadores) {
            return $contadores[$docente->codigo] ?? 0;
        })->first();
    }
}
