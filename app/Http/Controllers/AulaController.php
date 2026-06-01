<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Services\BitacoraService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AulaController extends Controller
{
    /**
     * Listado de aulas.
     */
    public function index()
    {
        $aulas = Aula::all();
        return view('admin.aulas.index', compact('aulas'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        return view('admin.aulas.create');
    }

    /**
     * Guardar nueva aula.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nro_aula' => [
                'required',
                'integer',
                Rule::unique('aulas', 'nro_aula')->where(function ($query) use ($request) {
                    return $query->where('modulo', $request->modulo)
                                 ->where('piso', $request->piso);
                }),
            ],
            'modulo' => ['required', 'string', 'max:50'],
            'piso'   => ['required', 'integer', 'min:0'],
        ], [
            'nro_aula.unique' => 'Ya existe un aula con ese número en el mismo módulo y piso.',
        ]);

        Aula::create($request->only(['nro_aula', 'modulo', 'piso']));

        BitacoraService::registrar('Creó aula: ' . $request->nro_aula . ' - ' . $request->modulo . ' - piso ' . $request->piso);

        return redirect()->route('admin.aulas.index')
            ->with('mensaje', 'Aula registrada exitosamente.')
            ->with('icono', 'success');
    }

    /**
     * Mostrar aula específica.
     */
    public function show(Aula $aula)
    {
        return view('admin.aulas.show', compact('aula'));
    }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $aula = Aula::findOrFail($id);
        return view('admin.aulas.edit', compact('aula'));
    }

    /**
     * Actualizar aula.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nro_aula' => [
                'required',
                'integer',
                Rule::unique('aulas', 'nro_aula')->where(function ($query) use ($request) {
                    return $query->where('modulo', $request->modulo)
                                 ->where('piso', $request->piso);
                })->ignore($id),
            ],
            'modulo' => ['required', 'string', 'max:50'],
            'piso'   => ['required', 'integer', 'min:0'],
        ], [
            'nro_aula.unique' => 'Ya existe un aula con ese número en el mismo módulo y piso.',
        ]);

        $aula = Aula::findOrFail($id);
        $aula->update($request->only(['nro_aula', 'modulo', 'piso']));

        BitacoraService::registrar('Actualizó aula: ' . $aula->nro_aula . ' - ' . $aula->modulo . ' - piso ' . $aula->piso);

        return redirect()->route('admin.aulas.index')
            ->with('mensaje', 'Aula actualizada exitosamente.')
            ->with('icono', 'success');
    }

    /**
     * Eliminar aula.
     */
    public function destroy($id)
    {
        $aula = Aula::findOrFail($id);
        $aula->delete();

        BitacoraService::registrar('Eliminó aula: ' . $aula->nro_aula . ' - ' . $aula->modulo . ' - piso ' . $aula->piso);

        return redirect()->route('admin.aulas.index')
            ->with('mensaje', 'Aula eliminada exitosamente.')
            ->with('icono', 'success');
    }
}
