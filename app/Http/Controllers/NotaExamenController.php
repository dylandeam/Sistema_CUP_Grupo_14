<?php

namespace App\Http\Controllers;

use App\Models\NotaExamen;
use App\Models\Examen;
use App\Models\Docente;
use App\Models\CargaHoraria;
use App\Models\Inscripcion;
use App\Models\Gestion;
use App\Models\Materia;
use Illuminate\Http\Request;

class NotaExamenController extends Controller
{
    /**
     * Mostrar formulario para registrar notas
     */
    public function create()
    {
        // Obtener la gestión activa
        $gestionActiva = Gestion::where('estado', 'Activa')->orderBy('semestre')->first();

        if (!$gestionActiva) {
            return view('admin.notas_examen.create', [
                'gestionActiva' => null,
                'examenes' => collect(),
                'cargasHorarias' => collect(),
                'mensaje' => 'No hay gestiones activas. No se pueden registrar notas hasta que exista una gestión activa.'
            ]);
        }

        // Obtener el docente autenticado
        $usuario = auth()->user();
        $docente = $usuario->docente ?? null;

        if (!$docente) {
            return view('admin.notas_examen.create', [
                'gestionActiva' => $gestionActiva,
                'examenes' => collect(),
                'cargasHorarias' => collect(),
                'mensaje' => 'No se encontró información del docente en el sistema.'
            ]);
        }

        // Obtener exámenes de la gestión activa
        $examenes = Examen::where('gestion_id', $gestionActiva->id)
            ->orderBy('nro_examen')
            ->get();

        // Obtener las cargas horarias que enseña el docente en esta gestión
        $cargasHorarias = CargaHoraria::where('docente_codigo', $docente->codigo)
            ->where('gestion_id', $gestionActiva->id)
            ->with(['materia', 'grupo', 'grupo.turno', 'grupo.modalidad'])
            ->get();

        // Obtener la materia para la cual el docente fue contratado (primera carga horaria)
        $materiaDocente = $cargasHorarias->first()?->materia;

        return view('admin.notas_examen.create', compact('gestionActiva', 'docente', 'examenes', 'cargasHorarias', 'materiaDocente'));
    }

    /**
     * Obtener inscritos de un grupo y materia específica
     */
    public function getInscritosPorGrupo(Request $request)
    {
        $grupoId = $request->input('grupo_id');
        $materiaId = $request->input('materia_id');
        $examenId = $request->input('examen_id');

        // Obtener gestión activa
        $gestionActiva = Gestion::where('estado', 'Activa')->first();

        if (!$gestionActiva) {
            return response()->json(['error' => 'No hay gestión activa'], 400);
        }

        // Obtener el grupo
        $grupo = \App\Models\Grupo::find($grupoId);
        if (!$grupo) {
            return response()->json(['error' => 'Grupo no encontrado'], 404);
        }

        // Obtener inscritos del grupo
        // Primero intentar por grupo_id (nuevos inscritos después de migración)
        $inscritos = Inscripcion::where('grupo_id', $grupo->id)
            ->with('postulante')
            ->orderBy('postulante_codigo')
            ->get();

        // Si no hay inscritos con grupo_id, buscar por modalidad+turno+gestion (inscritos antiguos)
        if ($inscritos->isEmpty()) {
            $inscritos = Inscripcion::where('gestion_id', $grupo->id_gestion)
                ->where('modalidad_id', $grupo->id_modalidad)
                ->where('turno_id', $grupo->id_turno)
                ->with('postulante')
                ->orderBy('postulante_codigo')
                ->get();
        }

        $materia = Materia::find($materiaId);

        $datos = [];
        foreach ($inscritos as $inscripcion) {
            $notaExamen = NotaExamen::where('id_inscripcion', $inscripcion->id)
                ->where('id_examen', $examenId)
                ->where('id_materia', $materiaId)
                ->first();

            $datos[] = [
                'id_inscripcion' => $inscripcion->id,
                'nombre' => $inscripcion->postulante->nombre . ' ' . $inscripcion->postulante->apellidos,
                'nota_materia' => $notaExamen->nota_materia ?? '',
                'nota_ponderada' => $notaExamen->nota_ponderada ?? '',
                'ponderacion' => $materia->ponderacion ?? 0,
            ];
        }

        return response()->json(['inscritos' => $datos]);
    }

    /**
     * Guardar notas de examen
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'examen_id' => 'required|exists:examenes,id',
            'materia_id' => 'required|exists:materias,id',
            'grupo_id' => 'required|exists:grupos,id',
            'notas' => 'required|array',
            'notas.*.id_inscripcion' => 'required|exists:inscripcions,id',
            'notas.*.nota_materia' => 'nullable|numeric|min:0|max:100',
        ]);

        $materia = Materia::find($validated['materia_id']);

        $contadorGuardados = 0;

        foreach ($validated['notas'] as $nota) {
            if ($nota['nota_materia'] !== null && $nota['nota_materia'] !== '') {
                // Calcular nota ponderada
                $notaPonderada = floatval($nota['nota_materia']) * floatval($materia->ponderacion);

                NotaExamen::updateOrCreate(
                    [
                        'id_examen' => $validated['examen_id'],
                        'id_materia' => $validated['materia_id'],
                        'id_inscripcion' => $nota['id_inscripcion'],
                    ],
                    [
                        'nota_materia' => floatval($nota['nota_materia']),
                        'nota_ponderada' => $notaPonderada,
                    ]
                );

                $contadorGuardados++;
            }
        }

        return redirect()->route('admin.notas_examen.create')
            ->with('mensaje', "Se registraron {$contadorGuardados} notas exitosamente.")
            ->with('icono', 'success');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(NotaExamen $nota_Examen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NotaExamen $nota_Examen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NotaExamen $nota_Examen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NotaExamen $nota_Examen)
    {
        //
    }
}

