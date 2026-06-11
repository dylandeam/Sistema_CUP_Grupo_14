<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Docente;
use App\Models\Gestion;
use App\Models\CargaHoraria;
use App\Models\Inscripcion;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AsistenciaController extends Controller
{
    /**
     * Mostrar formulario para registrar asistencias
     */
    public function index()
    {
        // Obtener la gestión activa
        $gestionActiva = Gestion::where('estado', 'Activa')->orderBy('semestre')->first();

        if (!$gestionActiva) {
            return view('admin.asistencias.index', [
                'gestionActiva' => null,
                'grupos' => collect(),
                'materiaDocente' => null,
                'mensaje' => 'No hay gestiones activas. No se pueden registrar asistencias hasta que exista una gestión activa.'
            ]);
        }

        // Obtener el docente autenticado
        $usuario = auth()->user();
        $docente = $usuario->docente ?? null;

        if (!$docente) {
            return view('admin.asistencias.index', [
                'gestionActiva' => $gestionActiva,
                'grupos' => collect(),
                'materiaDocente' => null,
                'mensaje' => 'No se encontró información del docente en el sistema.'
            ]);
        }

        // Obtener los grupos a los que el docente fue asignado en esta gestión
        $cargasHorarias = CargaHoraria::where('docente_codigo', $docente->codigo)
            ->where('gestion_id', $gestionActiva->id)
            ->with(['grupo', 'materia'])
            ->get();

        // Obtener grupos únicos desde las cargas horarias
        $grupos = $cargasHorarias->pluck('grupo')->unique('id')->values();
        
        // Obtener la materia para la cual el docente fue contratado (primera carga horaria)
        $materiaDocente = $cargasHorarias->first()?->materia;

        return view('admin.asistencias.index', compact('gestionActiva', 'docente', 'grupos', 'materiaDocente'));
    }

    /**
     * Obtener inscritos de un grupo específico
     */
    public function getInscritosPorGrupo(Request $request)
    {
        try {
            $grupoId = $request->input('grupo_id');

            // Validar parámetro
            if (!$grupoId) {
                return response()->json(['error' => 'Parámetro grupo_id requerido'], 400);
            }
            
            // Obtener gestión activa
            $gestionActiva = Gestion::where('estado', 'Activa')->first();

            if (!$gestionActiva) {
                return response()->json(['error' => 'No hay gestión activa'], 400);
            }

            // Obtener el grupo
            $grupo = Grupo::find($grupoId);
            if (!$grupo) {
                return response()->json(['error' => 'Grupo no encontrado'], 404);
            }

            // Obtener inscritos del grupo usando modalidad+turno+gestion
            $inscritos = Inscripcion::where('gestion_id', $grupo->id_gestion)
                ->where('modalidad_id', $grupo->id_modalidad)
                ->where('turno_id', $grupo->id_turno)
                ->with('postulante')
                ->orderBy('postulante_codigo')
                ->get();

            if ($inscritos->isEmpty()) {
                return response()->json(['inscritos' => []]);
            }

            $datos = [];
            foreach ($inscritos as $inscripcion) {
                // Verificar que el postulante existe
                if (!$inscripcion->postulante) {
                    continue;
                }

                $asistencia = Asistencia::where('codigo_postulante', $inscripcion->postulante_codigo)
                    ->where('id_grupo', $grupoId)
                    ->where('fecha', '>=', $gestionActiva->created_at)
                    ->latest('fecha')
                    ->first();

                $datos[] = [
                    'codigo_postulante' => $inscripcion->postulante_codigo,
                    'nombre' => $inscripcion->postulante->nombre . ' ' . $inscripcion->postulante->apellidos,
                    'estado' => $asistencia->estado ?? 'Presente',
                ];
            }

            return response()->json(['inscritos' => $datos]);
        } catch (\Exception $e) {
            Log::error('Error en Asistencia getInscritosPorGrupo: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Guardar asistencias
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'grupo_id' => 'required|exists:grupos,id',
            'asistencias' => 'required|array',
            'asistencias.*.codigo_postulante' => 'required|exists:postulantes,codigo',
            'asistencias.*.estado' => 'required|in:Presente,Falta,Licencia',
        ]);

        $contadorGuardados = 0;
        $fecha = now()->toDateString();

        foreach ($validated['asistencias'] as $asistencia) {
            Asistencia::updateOrCreate(
                [
                    'codigo_postulante' => $asistencia['codigo_postulante'],
                    'id_grupo' => $validated['grupo_id'],
                    'fecha' => $fecha,
                ],
                [
                    'estado' => $asistencia['estado'],
                ]
            );

            $contadorGuardados++;
        }

        return redirect()->route('admin.asistencias.index')
            ->with('mensaje', "Se registraron {$contadorGuardados} asistencias exitosamente.")
            ->with('icono', 'success');
    }

    /**
     * Marcar todos los inscritos como presentes automáticamente
     */
    public function marcarTodosPresentes(Request $request)
    {
        try {
            // Verificar autenticación
            if (!auth()->check()) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }

            $user = auth()->user();
            
            // Verificar que sea administrador o docente
            if (!$user->hasAnyRole(['ADMINISTRADOR', 'DOCENTE'])) {
                return response()->json(['error' => 'No tienes permiso para realizar esta acción'], 403);
            }

            // Obtener gestión activa
            $gestionActiva = Gestion::where('estado', 'Activa')->first();
            if (!$gestionActiva) {
                return response()->json(['error' => 'No hay gestión activa'], 400);
            }

            // Obtener todos los inscritos de la gestión
            $inscritos = Inscripcion::where('gestion_id', $gestionActiva->id)
                ->with('postulante')
                ->get();

            if ($inscritos->isEmpty()) {
                return response()->json(['error' => 'No hay inscritos para esta gestión'], 400);
            }

            $contadorAsistencias = 0;
            $fecha = now()->toDateString();

            // Obtener todos los grupos de la gestión
            $grupos = Grupo::where('id_gestion', $gestionActiva->id)->get();

            // Para cada inscrito
            foreach ($inscritos as $inscripcion) {
                // Para cada grupo de la gestión
                foreach ($grupos as $grupo) {
                    // Marcar como presente
                    Asistencia::updateOrCreate(
                        [
                            'codigo_postulante' => $inscripcion->postulante_codigo,
                            'id_grupo' => $grupo->id,
                            'fecha' => $fecha,
                        ],
                        [
                            'estado' => 'Presente',
                        ]
                    );

                    $contadorAsistencias++;
                }
            }

            Log::info('Asistencias marcadas como presentes', [
                'usuario_id' => $user->id,
                'conteo' => $contadorAsistencias,
                'inscritos' => $inscritos->count(),
                'grupos' => $grupos->count()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Se marcaron exitosamente {$contadorAsistencias} asistencias como presentes.",
                'conteo' => [
                    'inscritos' => $inscritos->count(),
                    'grupos' => $grupos->count(),
                    'asistencias_totales' => $contadorAsistencias
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error marcando todos presentes: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Asistencia $asistencia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asistencia $asistencia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asistencia $asistencia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asistencia $asistencia)
    {
        //
    }
}
