<?php

namespace App\Http\Controllers;

use App\Models\Modalidad;
use App\Services\BitacoraService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ModalidadController extends Controller
{
    /**
     * Mostrar listado de modalidades.
     */
    public function index()
    {
        $modalidades = Modalidad::all();
        return view('admin.modalidades.index', compact('modalidades'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        return view('admin.modalidades.create');
    }

    /**
     * Guardar nueva modalidad.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:modalidades,nombre',
        ], [
            'nombre.required' => 'El nombre de la modalidad es obligatorio.',
            'nombre.unique'   => 'Ya existe una modalidad con ese nombre.',
        ]);

        Modalidad::create($request->only(['nombre']));

        BitacoraService::registrar('Creó modalidad: ' . $request->nombre);

        return redirect()->route('admin.modalidades.index')
            ->with('mensaje', 'Modalidad registrada exitosamente.')
            ->with('icono', 'success');
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $modalidad = Modalidad::findOrFail($id);
        return view('admin.modalidades.edit', compact('modalidad'));
    }

    /**
     * Actualizar modalidad.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('modalidades')->ignore($id, 'id'),
            ],
        ], [
            'nombre.required' => 'El nombre de la modalidad es obligatorio.',
            'nombre.unique'   => 'Ya existe una modalidad con ese nombre.',
        ]);

        $modalidad = Modalidad::findOrFail($id);
        $modalidad->update($request->only(['nombre']));

        BitacoraService::registrar('Actualizó modalidad: ' . $modalidad->nombre);

        return redirect()->route('admin.modalidades.index')
            ->with('mensaje', 'Modalidad actualizada exitosamente.')
            ->with('icono', 'success');
    }

    /**
     * Eliminar modalidad.
     */
    public function destroy($id)
    {
        $modalidad = Modalidad::findOrFail($id);
        $modalidad->delete();

        BitacoraService::registrar('Eliminó modalidad: ' . $modalidad->nombre);

        return redirect()->route('admin.modalidades.index')
            ->with('mensaje', 'Modalidad eliminada exitosamente.')
            ->with('icono', 'success');
    }
}
