<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Gestion;
use App\Models\Modalidad;
use App\Models\Aula;
use App\Models\Turno;
use App\Models\Docente;
use App\Services\BitacoraService;
use App\Services\GrupoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Horario;
use App\Models\Materia;
use App\Models\CargaHoraria;
use Carbon\Carbon;

class GrupoController extends Controller
{
    /**
     * Listado de grupos.
     */
    public function index()
    {
        $gestionActiva = Gestion::where('estado', 'Activa')->orderBy('semestre')->first();

        if (! $gestionActiva) {
            $grupos = collect();
            $groupCountsByModalidad = collect();
            $activeGestionMessage = 'No hay gestiones activas. No se pueden generar grupos hasta que exista una gestión activa.';
        } else {
            if (! Grupo::where('id_gestion', $gestionActiva->id)->exists()) {
                GrupoService::generarGruposDesdeInscripcionesExistentes($gestionActiva->id);
            }

            $grupos = Grupo::with(['gestion','modalidad','aula','turno'])
                ->where('id_gestion', $gestionActiva->id)
                ->get();

            $groupCountsByModalidad = DB::table('grupos')
                ->join('modalidades', 'grupos.id_modalidad', '=', 'modalidades.id')
                ->where('grupos.id_gestion', $gestionActiva->id)
                ->select('modalidades.nombre', DB::raw('count(grupos.id) as total'))
                ->groupBy('modalidades.nombre')
                ->orderBy('modalidades.nombre')
                ->get();

            $activeGestionMessage = null;
        }

        return view('admin.grupos.index', compact('grupos', 'groupCountsByModalidad', 'activeGestionMessage', 'gestionActiva'));
    }

    /**
     * Mostrar grupo específico.
     */
    
    public function show(Grupo $grupo)
    {
        // Cargar relaciones necesarias
        $grupo->load(['gestion', 'turno', 'modalidad', 'aula']);

        // Contar ocupados - primero intenta por grupo_id, si no hay, usa modalidad+turno+gestion
        $ocupados = \App\Models\Inscripcion::where('grupo_id', $grupo->id)->count();
        
        if ($ocupados === 0) {
            $ocupados = \App\Models\Inscripcion::where('gestion_id', $grupo->id_gestion)
                ->where('modalidad_id', $grupo->id_modalidad)
                ->where('turno_id', $grupo->id_turno)
                ->count();
        }

        return view('admin.grupos.show', compact('grupo', 'ocupados'));
    }



    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $grupo = Grupo::findOrFail($id);

        // Solo la gestión activa
        $gestionActiva = Gestion::where('estado', 'Activa')->orderBy('semestre')->first();

        $modalidades = Modalidad::orderBy('nombre')->get();
        $aulas = Aula::orderBy('nro_aula')->get();
        $turnos = Turno::orderBy('nombre')->get();

        return view('admin.grupos.edit', compact('grupo', 'gestionActiva', 'modalidades', 'aulas', 'turnos'));
    }

    /**
     * Actualizar grupo.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'      => ['required','string','max:255'],
            'cupos'       => ['required','integer','min:1'],
            'id_gestion'  => ['required','exists:gestions,id'],
            'id_modalidad'=> ['required','exists:modalidades,id'],
            'id_turno'    => ['required','exists:turnos,id'],
            'id_aula'     => ['nullable','exists:aulas,id'],
        ]);

        $grupo = Grupo::findOrFail($id);

        // Actualizar campos
        $grupo->nombre = $request->nombre;
        $grupo->cupos = $request->cupos;
        $grupo->id_gestion = $request->id_gestion;
        $grupo->id_modalidad = $request->id_modalidad;
        $grupo->id_turno = $request->id_turno;

        // Aula: si es virtual, se guarda como null
        if (strtolower($grupo->modalidad->nombre) === 'virtual') {
            $grupo->id_aula = null;
        } else {
            $grupo->id_aula = $request->id_aula;
        }

        $grupo->save();

        BitacoraService::registrar('Actualizó grupo: ' . $grupo->nombre);

        return redirect()->route('admin.grupos.index')
            ->with('mensaje', 'Grupo actualizado exitosamente.')
            ->with('icono', 'success');
    }

    /**
     * Eliminar grupo.
     */
    public function destroy($id)
    {
        $grupo = Grupo::findOrFail($id);
        $grupo->delete();

        BitacoraService::registrar('Eliminó grupo: ' . $grupo->nombre);

        return redirect()->route('admin.grupos.index')
            ->with('mensaje', 'Grupo eliminado exitosamente.')
            ->with('icono', 'success');
    }

    public function horariosGrupo()
    {
        $gestionActiva = Gestion::where('estado', 'Activa')->orderBy('semestre')->first();

        if (! $gestionActiva) {
            $grupos = collect();
            $activeGestionMessage = 'No hay gestiones activas. No se pueden mostrar horarios de grupos hasta que exista una gestión activa.';
        } else {
            $grupos = Grupo::with(['gestion', 'modalidad', 'turno', 'aula'])
                ->where('id_gestion', $gestionActiva->id)
                ->get();

            $activeGestionMessage = null;
        }

        return view('admin.grupos.horariosgrupo', compact('grupos', 'gestionActiva', 'activeGestionMessage'));
    }

    public function showhorario(Grupo $grupo)
    {
        // Cargar relaciones necesarias
        $grupo->load(['gestion', 'turno', 'modalidad', 'aula']);

        // Obtener los horarios del turno del grupo
        $horarios = Horario::where('turno_id', $grupo->id_turno)
            ->orderBy('hora_inicio')
            ->get();

        // Obtener las 4 materias registradas
        $materias = Materia::orderBy('nombre')->take(4)->get();
        
        // Obtener docentes disponibles que enseñen estas materias
        $docentes = Docente::with('asignaciones.materia')
            ->whereHas('asignaciones', function ($query) use ($materias) {
                $query->whereIn('materia_id', $materias->pluck('id'));
            })
            ->get();

        // Construir matriz de horario semanal (lunes-viernes)
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        $horarioSemanal = [];

        // Si hay menos de 4 materias, rellenar con null para evitar errores
        $materiasCount = $materias->count();

        // Patrones de rotación por día para que cada día tenga las 4 materias en distinto orden
        $patronesPorDia = [
            'Lunes' => [0, 1, 3, 2],
            'Martes' => [1, 2, 3, 0],
            'Miércoles' => [2, 3, 1, 0],
            'Jueves' => [3, 1, 0, 2],
            'Viernes' => [0, 2, 1, 3],
        ];

        $periodoClase = 0;

        foreach ($horarios as $horario) {
            $horaKey = $horario->hora_inicio;
            $horarioSemanal[$horaKey] = [];

            // Detectar si es receso
            $duracion = Carbon::createFromFormat('H:i:s', $horario->hora_inicio)
                        ->diffInMinutes(Carbon::createFromFormat('H:i:s', $horario->hora_fin));
            $isReceso = $duracion < 20;

            if ($isReceso) {
                $horarioSemanal[$horaKey]['esReceso'] = true;
                $horarioSemanal[$horaKey]['duracion'] = $duracion;
            } else {
                $horarioSemanal[$horaKey]['esReceso'] = false;

                foreach ($dias as $dia) {
                    $indiceMateria = $patronesPorDia[$dia][$periodoClase % 4] ?? ($periodoClase % $materiasCount);
                    $indiceMateria = $materiasCount > 0 ? $indiceMateria % $materiasCount : null;
                    $materiaAsignada = $indiceMateria !== null ? $materias->get($indiceMateria) : null;
                    $docenteQueEnsena = null;

                    if ($materiaAsignada) {
                        $docenteQueEnsena = $docentes
                            ->filter(function ($doc) use ($materiaAsignada) {
                                return $doc->asignaciones->pluck('materia_id')->contains($materiaAsignada->id);
                            })
                            ->first();
                    }

                    $horarioSemanal[$horaKey][$dia] = [
                        'materia' => $materiaAsignada,
                        'docente' => $docenteQueEnsena,
                        'aula' => $grupo->aula,
                    ];
                }

                $periodoClase++;
            }
        }

        return view('admin.grupos.showhorario', compact('grupo', 'horarios', 'horarioSemanal', 'dias'));
    }

}
