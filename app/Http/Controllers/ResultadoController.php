<?php

namespace App\Http\Controllers;

use App\Models\Resultado;
use App\Models\Gestion;
use App\Models\Inscripcion;
use App\Models\NotaExamen;
use App\Models\Carrera;
use App\Models\Inscripcion_Carrera;
use Illuminate\Http\Request;

class ResultadoController extends Controller
{
    /**
     * Mostrar resumen de resultados (admitidos y no admitidos)
     */
    public function index()
    {
        $gestionActiva = Gestion::where('estado', 'Activa')->first();

        if (!$gestionActiva) {
            return view('admin.resultados.index', [
                'gestionActiva' => null,
                'admitidosCount' => 0,
                'noAdmitidosCount' => 0,
            ]);
        }

        // Generar resultados automáticamente
        $this->generarResultadosAutomatico($gestionActiva);

        // Contar admitidos y no admitidos
        $admitidosCount = Resultado::where('estado', 'ADMITIDO')->count();
        $noAdmitidosCount = Resultado::where('estado', 'NO ADMITIDO')->count();

        return view('admin.resultados.index', compact('gestionActiva', 'admitidosCount', 'noAdmitidosCount'));
    }

    /**
     * Generar resultados automáticamente
     */
    private function generarResultadosAutomatico($gestionActiva)
    {
        // Limpiar resultados previos
        Resultado::truncate();

        // Obtener todas las inscripciones de la gestión activa
        $inscripciones = Inscripcion::where('gestion_id', $gestionActiva->id)
            ->with('postulante', 'inscripcionCarreras')
            ->get();

        foreach ($inscripciones as $inscripcion) {
            // Obtener todas las notas del postulante
            $notasExamenes = NotaExamen::where('id_inscripcion', $inscripcion->id)
                ->get();

            // Si no hay notas, no es admitido
            if ($notasExamenes->isEmpty()) {
                Resultado::create([
                    'estado' => 'NO ADMITIDO',
                    'promedio_examen' => 0,
                    'id_inscripcion' => $inscripcion->id,
                    'id_inscripcion_carrera' => null,
                ]);
                continue;
            }

            // Verificar que todas las 4 materias tengan >= 60 en cada uno de los 3 exámenes
            $esAdmitido = true;
            
            // Agrupar por materia
            $notasPorMateria = $notasExamenes->groupBy('id_materia');
            
            // Verificar cada materia
            foreach ($notasPorMateria as $materiaId => $notasMateria) {
                // Debe haber 3 notas (una por cada examen)
                if ($notasMateria->count() !== 3) {
                    $esAdmitido = false;
                    break;
                }
                
                // Cada nota debe ser >= 60
                foreach ($notasMateria as $nota) {
                    if ($nota->nota_materia < 60) {
                        $esAdmitido = false;
                        break 2;
                    }
                }
            }

            // Si no cumple con el requisito de 4 materias, no es admitido
            if ($notasPorMateria->count() !== 4) {
                $esAdmitido = false;
            }

            // Si no pasó los criterios de materias, marcar como NO ADMITIDO
            if (!$esAdmitido) {
                Resultado::create([
                    'estado' => 'NO ADMITIDO',
                    'promedio_examen' => 0,
                    'id_inscripcion' => $inscripcion->id,
                    'id_inscripcion_carrera' => null,
                ]);
                continue;
            }

            // Calcular promedio general
            $promedioGeneral = $this->calcularPromedioGeneral($inscripcion->id, $gestionActiva->id);

            // Verificar si promedio general >= 60
            if ($promedioGeneral < 60) {
                Resultado::create([
                    'estado' => 'NO ADMITIDO',
                    'promedio_examen' => $promedioGeneral,
                    'id_inscripcion' => $inscripcion->id,
                    'id_inscripcion_carrera' => null,
                ]);
                continue;
            }

            // El postulante es ADMITIDO
            // Asignar carrera según disponibilidad
            $inscripcionCarreraAsignada = $this->asignarCarrera($inscripcion);

            if ($inscripcionCarreraAsignada) {
                Resultado::create([
                    'estado' => 'ADMITIDO',
                    'promedio_examen' => $promedioGeneral,
                    'id_inscripcion' => $inscripcion->id,
                    'id_inscripcion_carrera' => $inscripcionCarreraAsignada->id,
                ]);
            } else {
                // Si no hay carrera con cupos disponibles
                Resultado::create([
                    'estado' => 'NO ADMITIDO',
                    'promedio_examen' => $promedioGeneral,
                    'id_inscripcion' => $inscripcion->id,
                    'id_inscripcion_carrera' => null,
                ]);
            }
        }
    }

    /**
     * Calcular promedio general
     */
    private function calcularPromedioGeneral($inscripcionId, $gestionId)
    {
        $notasExamenes = NotaExamen::where('id_inscripcion', $inscripcionId)
            ->with('examen')
            ->get();

        $examenes = $notasExamenes
            ->groupBy('id_examen')
            ->map(function ($notas) {
                return [
                    'suma_ponderada' => $notas->sum('nota_ponderada'),
                    'examen' => $notas->first()->examen,
                ];
            });

        $promedioGeneral = 0;
        foreach ($examenes as $examenData) {
            $promedioGeneral += $examenData['suma_ponderada'] * $examenData['examen']->ponderacion;
        }

        return round($promedioGeneral, 0);
    }

    /**
     * Asignar carrera al postulante
     */
    private function asignarCarrera($inscripcion)
    {
        $inscripcionCarreras = $inscripcion->inscripcionCarreras()
            ->orderBy('orden_pref')
            ->get();

        foreach ($inscripcionCarreras as $inscripcionCarrera) {
            $carrera = $inscripcionCarrera->carrera;
            
            if ($carrera && $carrera->cupos_disponibles > 0) {
                // Decrementar cupos
                $carrera->decrement('cupos_disponibles');
                return $inscripcionCarrera;
            }
        }

        // Si ninguna opción tiene cupos, buscar carrera con cupos disponibles
        $carreraDisponible = Carrera::where('cupos_disponibles', '>', 0)
            ->first();

        if ($carreraDisponible) {
            $carreraDisponible->decrement('cupos_disponibles');
            
            // Crear registro en inscripcion_carrera
            $nuevaInscripcionCarrera = Inscripcion_Carrera::create([
                'orden_pref' => 3, // Tercera opción por defecto
                'inscripcion_id' => $inscripcion->id,
                'carrera_id' => $carreraDisponible->id,
            ]);
            
            return $nuevaInscripcionCarrera;
        }

        return null;
    }

    /**
     * Ver lista de admitidos
     */
    public function admitidos()
    {
        $gestionActiva = Gestion::where('estado', 'Activa')->first();

        if (!$gestionActiva) {
            return view('admin.resultados.admitidos', [
                'gestionActiva' => null,
                'admitidos' => collect(),
            ]);
        }

        $admitidos = Resultado::where('estado', 'ADMITIDO')
            ->with('inscripcion.postulante', 'inscripcionCarrera.carrera')
            ->get();

        return view('admin.resultados.admitidos', compact('gestionActiva', 'admitidos'));
    }

    /**
     * Ver lista de no admitidos
     */
    public function noAdmitidos()
    {
        $gestionActiva = Gestion::where('estado', 'Activa')->first();

        if (!$gestionActiva) {
            return view('admin.resultados.no_admitidos', [
                'gestionActiva' => null,
                'noAdmitidos' => collect(),
            ]);
        }

        $noAdmitidos = Resultado::where('estado', 'NO ADMITIDO')
            ->with('inscripcion.postulante')
            ->get();

        return view('admin.resultados.no_admitidos', compact('gestionActiva', 'noAdmitidos'));
    }

    /**
     * Debug - Ver información detallada del postulante
     */
    public function debug($inscripcionId)
    {
        $inscripcion = Inscripcion::with('postulante', 'gestion', 'inscripcionCarreras')->find($inscripcionId);

        if (!$inscripcion) {
            return response()->json(['error' => 'Inscripción no encontrada'], 404);
        }

        $notasExamenes = NotaExamen::where('id_inscripcion', $inscripcionId)
            ->with('examen', 'materia')
            ->get();

        // Agrupar por materia
        $materiasNotas = [];
        foreach ($notasExamenes as $nota) {
            $materiaId = $nota->id_materia;
            if (!isset($materiasNotas[$materiaId])) {
                $materiasNotas[$materiaId] = [
                    'materia' => $nota->materia->nombre,
                    'notas' => [],
                ];
            }
            $materiasNotas[$materiaId]['notas'][] = $nota->nota_materia;
            $materiasNotas[$materiaId]['maxima'] = max($materiasNotas[$materiaId]['notas']);
        }

        $estado = $this->determinarEstado($inscripcion, $inscripcion->gestion_id);

        return response()->json([
            'postulante' => $inscripcion->postulante->codigo . ' - ' . $inscripcion->postulante->nombre,
            'total_materias' => count($materiasNotas),
            'materias' => $materiasNotas,
            'resultado' => $estado,
            'promedio' => $this->calcularPromedioGeneral($inscripcionId, $inscripcion->gestion_id),
        ]);
    }

    /**
     * Determinar estado del postulante
     */
    private function determinarEstado($inscripcion, $gestionId)
    {
        // Obtener todas las notas del postulante
        $notasExamenes = NotaExamen::where('id_inscripcion', $inscripcion->id)
            ->with('examen', 'materia')
            ->get();

        // Si no hay notas, no es admitido
        if ($notasExamenes->isEmpty()) {
            return ['admitido' => false, 'promedio' => 0, 'carrera_id' => null];
        }

        // Agrupar notas por materia y obtener máximo
        $materiasNotas = [];
        foreach ($notasExamenes as $nota) {
            $materiaId = $nota->id_materia;
            if (!isset($materiasNotas[$materiaId])) {
                $materiasNotas[$materiaId] = $nota->nota_materia;
            } else {
                $materiasNotas[$materiaId] = max($materiasNotas[$materiaId], $nota->nota_materia);
            }
        }

        // Verificar que tenga exactamente 4 materias
        if (count($materiasNotas) != 4) {
            return ['admitido' => false, 'promedio' => 0, 'carrera_id' => null];
        }

        // Verificar que todas las materias tengan >= 60
        foreach ($materiasNotas as $nota) {
            if ($nota < 60) {
                return ['admitido' => false, 'promedio' => 0, 'carrera_id' => null];
            }
        }

        // Calcular promedio general
        $promedioGeneral = $this->calcularPromedioGeneral($inscripcion->id, $gestionId);

        // Verificar si promedio general >= 60
        if ($promedioGeneral < 60) {
            return ['admitido' => false, 'promedio' => $promedioGeneral, 'carrera_id' => null];
        }

        // El postulante es ADMITIDO
        // Asignar carrera según disponibilidad
        $inscripcionCarreraAsignada = $this->asignarCarrera($inscripcion);

        if ($inscripcionCarreraAsignada) {
            return ['admitido' => true, 'promedio' => $promedioGeneral, 'carrera_id' => $inscripcionCarreraAsignada->id];
        }

        return ['admitido' => false, 'promedio' => $promedioGeneral, 'carrera_id' => null];
    }

    /**
     * Cerrar la gestión activa
     */
    public function cerrar(Request $request)
    {
        $gestionActiva = Gestion::where('estado', 'Activa')->first();

        if (!$gestionActiva) {
            return redirect()->route('admin.resultados.index')
                ->with('error', 'No hay gestión activa para cerrar.');
        }

        $gestionActiva->update(['estado' => 'Cerrada']);

        return redirect()->route('admin.resultados.index')
            ->with('mensaje', 'Gestión cerrada correctamente.')
            ->with('icono', 'success');
    }
}
