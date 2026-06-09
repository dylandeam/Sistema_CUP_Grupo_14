<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use App\Services\BitacoraService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TurnoController extends Controller
{
    /**
     * Mostrar listado de turnos.
     */
    public function index()
    {
        $turnos = Turno::all();
        return view('admin.turnos.index', compact('turnos'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        return view('admin.turnos.create');
    }

    /**
     * Guardar nuevo turno.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:turnos,nombre',
        ], [
            'nombre.required' => 'El nombre del turno es obligatorio.',
            'nombre.unique'   => 'Ya existe un turno con ese nombre.',
        ]);

        Turno::create($request->only(['nombre']));

        BitacoraService::registrar('Creó turno: ' . $request->nombre);

        return redirect()->route('admin.turnos.index')
            ->with('mensaje', 'Turno registrado exitosamente.')
            ->with('icono', 'success');
    }


    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $turno = Turno::findOrFail($id);
        return view('admin.turnos.edit', compact('turno'));
    }

    /**
     * Actualizar turno.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('turnos')->ignore($id, 'id'),
            ],
        ], [
            'nombre.required' => 'El nombre del turno es obligatorio.',
            'nombre.unique'   => 'Ya existe un turno con ese nombre.',
        ]);

        $turno = Turno::findOrFail($id);
        $turno->update($request->only(['nombre']));

        BitacoraService::registrar('Actualizó turno: ' . $turno->nombre);

        return redirect()->route('admin.turnos.index')
            ->with('mensaje', 'Turno actualizado exitosamente.')
            ->with('icono', 'success');
    }

    /**
     * Eliminar turno.
     */
    public function destroy($id)
    {
        $turno = Turno::findOrFail($id);
        $turno->delete();

        BitacoraService::registrar('Eliminó turno: ' . $turno->nombre);

        return redirect()->route('admin.turnos.index')
            ->with('mensaje', 'Turno eliminado exitosamente.')
            ->with('icono', 'success');
    }
}
