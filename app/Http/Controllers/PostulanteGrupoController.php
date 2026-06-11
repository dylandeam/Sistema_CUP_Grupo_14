<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Inscripcion;
use App\Models\Gestion;
use Illuminate\Http\Request;

class PostulanteGrupoController extends Controller
{
    /**
     * Mostrar listado de grupos con sus detalles
     */
    public function index()
    {
        // Obtener la gestión activa
        $gestionActiva = Gestion::where('estado', 'Activa')->first();

        if (!$gestionActiva) {
            return view('admin.postulante_grupos.index', [
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
            // Intentar por grupo_id primero
            $inscritos = Inscripcion::where('grupo_id', $grupo->id)->count();
            
            // Si no hay, usar modalidad+turno+gestion
            if ($inscritos === 0) {
                $inscritos = Inscripcion::where('gestion_id', $grupo->id_gestion)
                    ->where('modalidad_id', $grupo->id_modalidad)
                    ->where('turno_id', $grupo->id_turno)
                    ->count();
            }
            
            $grupo->total_inscritos = $inscritos;
            return $grupo;
        });

        return view('admin.postulante_grupos.index', compact('gestionActiva', 'grupos'));
    }

    /**
     * Mostrar postulantes asignados a un grupo específico
     */
    public function show($id)
    {
        // Obtener el grupo por ID
        $grupo = Grupo::findOrFail($id);
        
        // Obtener la gestión activa
        $gestionActiva = Gestion::where('estado', 'Activa')->first();

        if (!$gestionActiva) {
            return redirect()->route('admin.postulante_grupos.index')
                ->with('mensaje', 'No hay gestiones activas.');
        }

        // Cargar modalidad y turno
        $grupo->load(['modalidad', 'turno']);

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

        return view('admin.postulante_grupos.show', compact('grupo', 'inscritos', 'gestionActiva'));
    }
}
