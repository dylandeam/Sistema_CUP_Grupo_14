<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Services\BitacoraService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CarreraController extends Controller
{
    /**
     * Mostrar listado de carreras.
     */
    public function index()
    {
        $carreras = Carrera::all();
        return view('admin.carreras.index', compact('carreras'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        return view('admin.carreras.create');
    }

    /**
     * Guardar nueva carrera.
     */
   
    public function store(Request $request)
{
    $request->validate([
        'sigla' => 'required|string|max:10|unique:carreras,sigla',
        'nombre' => 'required|string|max:255|unique:carreras,nombre',
        'cupos_disponibles' => 'required|integer|min:0',
    ], [
        'sigla.unique' => 'La sigla ya está en uso.',
        'nombre.unique' => 'El nombre ya está en uso.',
    ]);
    
    Carrera::create($request->only(['sigla', 'nombre', 'cupos_disponibles']));

    BitacoraService::registrar('Creó carrera: ' . $request->nombre);

    return redirect()->route('admin.carreras.index')
        ->with('mensaje', 'Carrera registrada exitosamente.')
        ->with('icono', 'success');
}


    /**
     * Mostrar una carrera específica.
     */
    public function show(Carrera $carrera)
    {
        return view('admin.carreras.show', compact('carrera'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $carrera = Carrera::findOrFail($id);
        return view('admin.carreras.edit', compact('carrera'));
    }

    /**
     * Actualizar carrera.
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'sigla' => [
            'required',
            'string',
            'max:10',
            Rule::unique('carreras', 'sigla')->ignore($id),
        ],
        'nombre' => [
            'required',
            'string',
            'max:255',
            Rule::unique('carreras', 'nombre')->ignore($id),
        ],
        'cupos_disponibles' => 'required|integer|min:0',
    ], [
        'sigla.unique' => 'La sigla ya está en uso.',
        'nombre.unique' => 'El nombre ya está en uso.',
    ]);

    $carrera = Carrera::findOrFail($id);
    $carrera->update($request->only(['sigla', 'nombre', 'cupos_disponibles']));

    BitacoraService::registrar('Actualizó carrera: ' . $carrera->nombre);

    return redirect()->route('admin.carreras.index')
        ->with('mensaje', 'Carrera actualizada exitosamente.')
        ->with('icono', 'success');
}


    /**
     * Eliminar carrera.
     */
    public function destroy($id)
    {
        $carrera = Carrera::findOrFail($id);
        $carrera->delete();

        BitacoraService::registrar('Eliminó carrera: ' . $carrera->nombre);

        return redirect()->route('admin.carreras.index')
            ->with('mensaje', 'Carrera eliminada exitosamente.')
            ->with('icono', 'success');
    }
}
