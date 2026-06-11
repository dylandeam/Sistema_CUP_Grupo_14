<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Inscripcion;
use App\Models\Gestion;
use App\Models\NotaExamen;
use App\Models\Examen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromedioExamenController extends Controller
{
    /**
     * Listado de grupos para ver promedios de exámenes
     */
    public function index()
    {
        // Obtener la gestión activa
        $gestionActiva = Gestion::where('estado', 'Activa')->first();

        if (!$gestionActiva) {
            return view('admin.promedios_examen.index', [
                'gestionActiva' => null,
                'grupos' => collect(),
                'mensaje' => 'No hay gestiones activas.'
            ]);
        }

        // Obtener todos los grupos de la gestión activa
        $grupos = Grupo::where('id_gestion', $gestionActiva->id)
            ->with(['modalidad', 'turno'])
            ->orderBy('nombre')
            ->get();

        // Contar inscritos por grupo
        $grupos = $grupos->map(function ($grupo) {
            $inscritos = Inscripcion::where('grupo_id', $grupo->id)->count();
            
            $grupo->total_inscritos = $inscritos;
            return $grupo;
        });

        return view('admin.promedios_examen.index', compact('gestionActiva', 'grupos'));
    }

    /**
     * Mostrar promedios de exámenes para un grupo específico
     */
   public function show($id)
{
    $grupo = Grupo::findOrFail($id);
    $gestionActiva = Gestion::where('estado', 'Activa')->first();

    if (!$gestionActiva) {
        return redirect()->route('admin.promedios_examen.index')
            ->with('mensaje', 'No hay gestiones activas.');
    }

    $grupo->load(['modalidad', 'turno']);

    $examenes = Examen::where('gestion_id', $gestionActiva->id)
        ->orderBy('nro_examen')
        ->get();

    // Obtener inscritos del grupo
    // Primero intentar por grupo_id (nuevos inscritos después de migración)
    $inscritos = Inscripcion::where('grupo_id', $grupo->id)
        ->with('postulante')
        ->orderBy('postulante_codigo')
        ->get();

    // Si no hay inscritos con grupo_id, buscar por modalidad+turno+gestion (inscritos antiguos)
    if ($inscritos->isEmpty()) {
        $inscritos = Inscripcion::where('gestion_id', $gestionActiva->id)
            ->where('modalidad_id', $grupo->id_modalidad)
            ->where('turno_id', $grupo->id_turno)
            ->with('postulante')
            ->orderBy('postulante_codigo')
            ->get();
    }

    $postulantesProcesados = [];

    foreach ($inscritos as $inscripcion) {
        $postulante = [
            'inscripcion_id' => $inscripcion->id,
            'nombre' => $inscripcion->postulante->nombre . ' ' . $inscripcion->postulante->apellidos,
            'codigo' => $inscripcion->postulante->codigo,
            'examenes' => [],
            'promedioGeneral' => 0,
        ];

        // Calcular nota por cada examen
        foreach ($examenes as $examen) {
            $notasExamen = NotaExamen::where('id_inscripcion', $inscripcion->id)
                ->where('id_examen', $examen->id)
                ->get();

            // Sumar las notas ponderadas de las materias de ese examen
            $sumaPonderada = $notasExamen->sum('nota_ponderada');

            $postulante['examenes'][$examen->nro_examen] = [
                'nro_examen' => $examen->nro_examen,
                'suma_ponderada' => round($sumaPonderada, 2),
                'ponderacion_examen' => $examen->ponderacion, // ya está como 0.3, 0.4
            ];
        }

        // Calcular promedio general con ponderaciones (sin dividir entre 100)
        $sumaPromedios = 0;
        foreach ($postulante['examenes'] as $examenData) {
            $sumaPromedios += $examenData['suma_ponderada'] * $examenData['ponderacion_examen'];
        }

        // Redondear al entero más cercano
        $postulante['promedioGeneral'] = round($sumaPromedios);

        $postulantesProcesados[] = $postulante;
    }

    return view('admin.promedios_examen.show', compact('grupo', 'postulantesProcesados', 'gestionActiva', 'examenes'));
}

}
