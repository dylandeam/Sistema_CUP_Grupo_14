<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\Docente_Materia;
use App\Models\Materia;
use App\Models\DocenteMateria;
use App\Services\BitacoraService;
use Illuminate\Http\Request;

class DocenteMateriaController extends Controller
{
    /**
     * Listado de asignaciones
     */
    public function index()
    {
        $asignaciones = Docente_Materia::with(['docente','materia'])->get();
        return view('admin.docente_materia.index', compact('asignaciones'));
    }

    /**
     * Formulario para asignar materias a un docente
     */
    public function create($docente_id)
    {
        $docente = Docente::findOrFail($docente_id);
        $materias = Materia::all();

        return view('admin.docente_materia.create', compact('docente','materias'));
    }

    /**
     * Guardar asignación
     */
    public function store(Request $request, $docente_id)
    {
        $request->validate([
            'materia_id' => 'required|exists:materias,id',
            'estado'     => 'required|in:contratado,baja',
        ]);

        Docente_Materia::create([
            'docente_id' => $docente_id,
            'materia_id' => $request->materia_id,
            'estado'     => $request->estado,
        ]);

        BitacoraService::registrar('Asignó materia al docente ID ' . $docente_id);

        return redirect()->route('admin.docentes.index')
            ->with('mensaje','Materia asignada correctamente al docente')
            ->with('icono','success');
    }

    /**
     * Editar asignación
     */
    public function edit($id)
    {
        $asignacion = Docente_Materia::findOrFail($id);
        $materias = Materia::all();

        return view('admin.docente_materia.edit', compact('asignacion','materias'));
    }

    /**
     * Actualizar asignación
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'materia_id' => 'required|exists:materias,id',
            'estado'     => 'required|in:contratado,baja',
        ]);

        $asignacion = Docente_Materia::findOrFail($id);
        $asignacion->update([
            'materia_id' => $request->materia_id,
            'estado'     => $request->estado,
        ]);

        BitacoraService::registrar('Actualizó asignación de materia ID ' . $asignacion->id);

        return redirect()->route('docente_materia.index')
            ->with('mensaje','Asignación actualizada correctamente')
            ->with('icono','success');
    }

    /**
     * Eliminar asignación
     */
    public function destroy($id)
    {
        $asignacion = Docente_Materia::findOrFail($id);
        $asignacion->delete();

        BitacoraService::registrar('Eliminó asignación de materia ID ' . $asignacion->id);

        return back()->with('mensaje','Asignación eliminada correctamente')
                     ->with('icono','success');
    }
}
