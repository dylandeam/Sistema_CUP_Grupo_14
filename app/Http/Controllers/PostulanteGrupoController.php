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
        $grupos = $grupos->map(function ($grupo) use ($gestionActiva) {
            $inscritos = Inscripcion::where('modalidad_id', $grupo->id_modalidad)
                ->where('turno_id', $grupo->id_turno)
                ->where('gestion_id', $gestionActiva->id)
                ->count();
            
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

        // Obtener inscritos del grupo (por modalidad, turno y gestión)
        $inscritos = Inscripcion::where('modalidad_id', $grupo->id_modalidad)
            ->where('turno_id', $grupo->id_turno)
            ->where('gestion_id', $gestionActiva->id)
            ->with('postulante')
            ->orderBy('postulante_codigo')
            ->get();

        return view('admin.postulante_grupos.show', compact('grupo', 'inscritos', 'gestionActiva'));
    }
}
