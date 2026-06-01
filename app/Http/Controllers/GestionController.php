<?php

namespace App\Http\Controllers;

use App\Models\Gestion;
use App\Services\BitacoraService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GestionController extends Controller
{
    /**
     * Listado de gestiones.
     */
    public function index()
    {
        $gestiones = Gestion::all();
        return view('admin.gestiones.index', compact('gestiones'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        return view('admin.gestiones.create');
    }

    /**
     * Guardar nueva gestión.
     */

    public function store(Request $request)
{
    $request->merge([
        'año' => $request->input('año', $request->input('anio')),
    ]);

    $request->validate([
        'semestre' => ['required', 'in:1,2'],
        'año' => [
            'required',
            'integer',
            'digits:4',
            'min:1900',
            'max:' . date('Y'),
            Rule::unique('gestions', 'año')->where(function ($query) use ($request) {
                return $query->where('semestre', $request->semestre);
            }),
        ],
        'estado' => ['required', 'in:Activa,Cerrada'],
    ], [
        'año.unique' => 'La Gestión ya existe.',
    ]);

    if ($request->estado === 'Activa') {
        Gestion::where('estado', 'Activa')->update(['estado' => 'Cerrada']);
    }

    Gestion::create($request->only(['semestre', 'año', 'estado']));

    BitacoraService::registrar('Creó gestión: ' . $request->semestre . ' / ' . $request->input('año'));

    return redirect()->route('admin.gestiones.index')
        ->with('mensaje', 'Gestión creada exitosamente.')
        ->with('icono', 'success');
}

    /**
     * Mostrar una gestión específica.
     */
    public function show(Gestion $gestion)
    {
        return view('admin.gestiones.show', compact('gestion'));
    }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $gestion = Gestion::findOrFail($id);
        return view('admin.gestiones.edit', compact('gestion'));
    }

    /**
     * Actualizar gestión.
     */
    public function update(Request $request, $id)
    {
        $request->merge([
            'año' => $request->input('año', $request->input('anio')),
        ]);

        $request->validate([
        'semestre' => ['required', 'in:1,2'],
        'año' => [
            'required',
            'integer',
            'digits:4',
            'min:1900',
            'max:' . date('Y'),
            Rule::unique('gestions', 'año')->where(function ($query) use ($request) {
                return $query->where('semestre', $request->semestre);
            })->ignore($id),
        ],
        'estado' => ['required', 'in:Activa,Cerrada'],
    ], [
        'año.unique' => 'La Gestión ya existe.',
    ]);
    $gestion = Gestion::findOrFail($id);
    $gestion->update($request->only(['semestre', 'año', 'estado']));

    BitacoraService::registrar('Actualizó gestión: ' . $gestion->semestre . ' / ' . $gestion->año);

      return redirect()->route('admin.gestiones.index')
        ->with('mensaje', 'Gestión actualizada exitosamente.')
        ->with('icono', 'success');
    }

    /**
     * Eliminar gestión.
     */
    public function destroy($id)
    {
        $gestion = Gestion::findOrFail($id);
        $gestion->delete();

        BitacoraService::registrar('Eliminó gestión: ' . $gestion->semestre . ' / ' . $gestion->año);

        return redirect()->route('admin.gestiones.index')
            ->with('mensaje', 'Gestión eliminada exitosamente.')
            ->with('icono', 'success');
    }
}
