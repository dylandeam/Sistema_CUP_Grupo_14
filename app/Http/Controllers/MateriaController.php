<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Services\BitacoraService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MateriaController extends Controller
{
    /**
     * Mostrar listado de materias.
     */
    public function index()
    {
        $materias = Materia::all();
        return view('admin.materias.index', compact('materias'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        return view('admin.materias.create');
    }

    /**
     * Guardar nueva materia.
     */
    public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255|unique:materias,nombre',
        'ponderacion' => 'required|numeric|min:0|max:1', 
    ], [
        'nombre.unique' => 'El nombre de la materia ya está en uso.',
    ]);

    Materia::create($request->only(['nombre', 'ponderacion']));

    BitacoraService::registrar('Creó materia: ' . $request->nombre);

    return redirect()->route('admin.materias.index')
        ->with('mensaje', 'Materia creada exitosamente.')
        ->with('icono', 'success');
}

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $materia = Materia::findOrFail($id);
        return view('admin.materias.edit', compact('materia'));
    }

    /**
     * Actualizar materia.
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'nombre' => [
            'required',
            'string',
            'max:255',
            Rule::unique('materias', 'nombre')->ignore($id),
        ],
        'ponderacion' => 'required|numeric|min:0|max:1',
    ], [
        'nombre.unique' => 'El nombre de la materia ya está en uso.',
    ]);

    $materia = Materia::findOrFail($id);
    $materia->update($request->only(['nombre', 'ponderacion']));

    BitacoraService::registrar('Actualizó materia: ' . $materia->nombre);

    return redirect()->route('admin.materias.index')
        ->with('mensaje', 'Materia actualizada exitosamente.')
        ->with('icono', 'success');
}


    /**
     * Eliminar materia.
     */
    public function destroy($id)
    {
        $materia = Materia::findOrFail($id);
        $materia->delete();

        BitacoraService::registrar('Eliminó materia: ' . $materia->nombre);

        return redirect()->route('admin.materias.index')
            ->with('mensaje', 'Materia eliminada exitosamente.')
            ->with('icono', 'success');
    }
}

