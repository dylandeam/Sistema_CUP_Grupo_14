<?php

namespace App\Services;

use App\Models\CargaHoraria;
use App\Models\Grupo;
use App\Models\Inscripcion;
use App\Models\Turno;
use App\Models\Gestion;
use App\Models\Modalidad;
use App\Models\Aula;
use App\Models\Horario;
use App\Models\Materia;
use Illuminate\Support\Facades\DB;

class GrupoService
{
    public static function generarGrupoParaInscripcion(Inscripcion $inscripcion): void
    {
        if (! $inscripcion->turno_id || ! $inscripcion->modalidad_id || ! $inscripcion->gestion_id) {
            return;
        }

        // 1. Crear grupos si es necesario
        self::generarGruposParaCombo(
            $inscripcion->gestion_id,
            $inscripcion->modalidad_id,
            $inscripcion->turno_id
        );

        // 2. Asignar inscritos a grupos
        self::asignarInscritosAGrupos(
            $inscripcion->gestion_id,
            $inscripcion->modalidad_id,
            $inscripcion->turno_id
        );
    }

    public static function generarGruposDesdeInscripcionesExistentes(?int $gestionId = null): void
    {
        $query = Inscripcion::select('gestion_id', 'modalidad_id', 'turno_id')
            ->whereNotNull('gestion_id')
            ->whereNotNull('modalidad_id')
            ->whereNotNull('turno_id');

        if ($gestionId !== null) {
            $query->where('gestion_id', $gestionId);
        }

        $combos = $query->distinct()->get();

        foreach ($combos as $combo) {
            self::generarGruposParaCombo(
                $combo->gestion_id,
                $combo->modalidad_id,
                $combo->turno_id
            );
        }
    }

    private static function generarGruposParaCombo(int $gestionId, int $modalidadId, int $turnoId): void
    {
        $gestion = Gestion::find($gestionId);
        $modalidad = Modalidad::find($modalidadId);
        $turno = Turno::find($turnoId);

        if (! $gestion || ! $modalidad || ! $turno) {
            return;
        }

        $capacidad = config('inscripcion.cupos_grupo', 70);
        $inscritoCount = Inscripcion::where('gestion_id', $gestionId)
            ->where('modalidad_id', $modalidadId)
            ->where('turno_id', $turnoId)
            ->count();

        if ($inscritoCount === 0) {
            return;
        }

        $neededGroups = intdiv(max($inscritoCount - 1, 0), $capacidad) + 1;
        $turnoPrefix = self::obtenerPrefijoTurno($turno->nombre);

        $materias = self::obtenerMateriasNecesarias();
        if (count($materias) < 4) {
            return;
        }

        $horariosPorTurno = Horario::all()->groupBy('turno_id');
        $aulas = Aula::orderBy('nro_aula')->get();

        for ($grupoIndex = 1; $grupoIndex <= $neededGroups; $grupoIndex++) {
            $nombre = sprintf('%s%03d', $turnoPrefix, $grupoIndex);

            $grupo = Grupo::firstOrNew([
                'id_gestion' => $gestionId,
                'id_modalidad' => $modalidadId,
                'id_turno' => $turnoId,
                'nombre' => $nombre,
            ]);

            $grupo->cupos = $capacidad;

            if (strtolower($modalidad->nombre) === 'virtual') {
                // Modalidad virtual no usa aulas
                $grupo->id_aula = null;
            } else {
                // Modalidad presencial: asignar aulas distintas por turno
                if ($aulas->isNotEmpty()) {
                    // Buscar aulas ya usadas en este turno
                    $aulasUsadas = Grupo::where('id_gestion', $gestionId)
                        ->where('id_modalidad', $modalidadId)
                        ->where('id_turno', $turnoId)
                        ->pluck('id_aula')
                        ->filter();

                    // Seleccionar la primera aula disponible que no esté usada
                    $aulaDisponible = $aulas->first(function ($aula) use ($aulasUsadas) {
                        return ! $aulasUsadas->contains($aula->id);
                    });

                    if ($aulaDisponible) {
                        $grupo->id_aula = $aulaDisponible->id;
                    }
                }
            }

            if (! $grupo->exists || $grupo->isDirty(['cupos', 'id_aula'])) {
                $grupo->save();
            }

            self::generarCargaHorariaParaGrupo($grupo, $materias, $horariosPorTurno);
        }
    }

    private static function generarCargaHorariaParaGrupo(Grupo $grupo, array $materias, $horariosPorTurno): void
    {
        if (CargaHoraria::where('grupo_id', $grupo->id)->exists()) {
            return;
        }

        $hora = $horariosPorTurno->get($grupo->id_turno)?->first();
        if (! $hora) {
            $hora = self::crearHorarioPorDefecto($grupo->id_turno);
        }

        if (! $hora) {
            return;
        }

        $asignados = [];
        $docenteContador = CargaHoraria::select('docente_codigo', DB::raw('count(distinct grupo_id) as grupos'))
            ->groupBy('docente_codigo')
            ->pluck('grupos', 'docente_codigo')
            ->toArray();

        DB::transaction(function () use ($grupo, $materias, $hora, &$docenteContador, &$asignados) {
            foreach ($materias as $materia) {
                $docente = self::seleccionarDocenteDisponible($materia, $docenteContador, $asignados);
                if (! $docente) {
                    return;
                }

                CargaHoraria::create([
                    'docente_codigo' => $docente->codigo,
                    'materia_id'     => $materia->id,
                    'grupo_id'       => $grupo->id,
                    'horario_id'     => $hora->id,
                    'aula_id'        => $grupo->id_aula,
                    'gestion_id'     => $grupo->id_gestion,
                ]);

                $asignados[] = $docente->codigo;
                $docenteContador[$docente->codigo] = ($docenteContador[$docente->codigo] ?? 0) + 1;
            }
        });
    }

    private static function obtenerMateriasNecesarias(): array
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

    private static function seleccionarDocenteDisponible(Materia $materia, array $contadores, array $excluidos)
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

    private static function crearHorarioPorDefecto(int $turnoId): ?Horario
    {
        $turno = Turno::find($turnoId);
        if (! $turno) {
            return null;
        }

        return Horario::firstOrCreate([
            'turno_id' => $turnoId,
            'hora_inicio' => '08:00:00',
            'hora_fin' => '10:00:00',
        ]);
    }

    private static function obtenerPrefijoTurno(string $turnoNombre): string
    {
        $nombre = trim(mb_strtolower($turnoNombre));

        if (str_contains($nombre, 'mañana') || str_starts_with($nombre, 'm')) {
            return 'M';
        }

        if (str_contains($nombre, 'tarde') || str_starts_with($nombre, 't')) {
            return 'T';
        }

        if (str_contains($nombre, 'noche') || str_starts_with($nombre, 'n')) {
            return 'N';
        }

        // Fallback: primera letra del turno en mayúscula
        return strtoupper(mb_substr($nombre, 0, 1));
    }

    /**
     * Asignar inscritos a grupos de manera ordenada y sin duplicación.
     * Distribuye máximo 70 inscritos por grupo.
     */
    private static function asignarInscritosAGrupos(int $gestionId, int $modalidadId, int $turnoId): void
    {
        $capacidad = config('inscripcion.cupos_grupo', 70);

        // Obtener los grupos para esta combinación, ordenados por nombre
        $grupos = Grupo::where('id_gestion', $gestionId)
            ->where('id_modalidad', $modalidadId)
            ->where('id_turno', $turnoId)
            ->orderBy('nombre')
            ->get();

        if ($grupos->isEmpty()) {
            return;
        }

        // Obtener todos los inscritos de esta combinación, ordenados por ID (para consistencia)
        $inscritos = Inscripcion::where('gestion_id', $gestionId)
            ->where('modalidad_id', $modalidadId)
            ->where('turno_id', $turnoId)
            ->orderBy('id')
            ->get();

        if ($inscritos->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($inscritos, $grupos, $capacidad) {
            $grupoIndex = 0;
            $contadorPorGrupo = [];

            // Inicializar contadores de inscritos por grupo
            foreach ($grupos as $index => $grupo) {
                // Contar inscritos ya asignados a este grupo
                $contadorPorGrupo[$grupo->id] = Inscripcion::where('grupo_id', $grupo->id)->count();
            }

            // Distribuir inscritos
            foreach ($inscritos as $inscripcion) {
                // Si el inscrito ya tiene grupo asignado, saltar
                if ($inscripcion->grupo_id !== null) {
                    continue;
                }

                // Buscar el primer grupo que tenga espacio
                $grupoAsignado = null;
                foreach ($grupos as $grupo) {
                    $ocupados = $contadorPorGrupo[$grupo->id] ?? 0;
                    if ($ocupados < $capacidad) {
                        $grupoAsignado = $grupo;
                        break;
                    }
                }

                // Si no hay grupo con espacio, algo está mal (no debería ocurrir)
                if (!$grupoAsignado) {
                    continue;
                }

                // Asignar el inscrito a este grupo
                $inscripcion->update(['grupo_id' => $grupoAsignado->id]);
                $contadorPorGrupo[$grupoAsignado->id]++;
            }
        });
    }
}
