<?php

namespace App\Http\Controllers;

use App\Models\Examen;
use App\Models\Gestion;
use App\Services\BitacoraService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExamenController extends Controller
{
    /**
     * Mostrar listado de exámenes.
     */
    public function index()
    {
        $examenes = Examen::with('gestion')->get();
        $gestionActiva = Gestion::where('estado', 'Activa')->first();

        return view('admin.examenes.index', compact('examenes', 'gestionActiva'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        $gestionActiva = Gestion::where('estado', 'Activa')->first();
        return view('admin.examenes.create', compact('gestionActiva'));
    }

    /**
     * Guardar nuevo examen.
     */
    public function store(Request $request)
    {
        $gestionActiva = Gestion::where('estado', 'Activa')->first();

        if (!$gestionActiva) {
            return redirect()->back()
                ->with('mensaje', 'No hay gestiones activas, no se puede registrar el examen.')
                ->with('icono', 'error');
        }

        $request->validate([
            'nro_examen' => 'required|string|max:50|unique:examenes,nro_examen',
            'fecha'      => 'required|date',
            'ponderacion'=> 'required|numeric|min:0|max:1',
        ], [
            'nro_examen.unique' => 'El número de examen ya está en uso.',
        ]);

        Examen::create([
            'nro_examen'  => $request->nro_examen,
            'fecha'       => $request->fecha,
            'ponderacion' => $request->ponderacion,
            'gestion_id'  => $gestionActiva->id,
        ]);

        BitacoraService::registrar('Creó examen: ' . $request->nro_examen);

        return redirect()->route('admin.examenes.index')
            ->with('mensaje', 'Examen creado exitosamente.')
            ->with('icono', 'success');
    }


    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $examen = Examen::findOrFail($id);
        $gestionActiva = Gestion::where('estado', 'Activa')->first();

        return view('admin.examenes.edit', compact('examen', 'gestionActiva'));
    }

    /**
     * Actualizar examen.
     */
    public function update(Request $request, $id)
    {
        $gestionActiva = Gestion::where('estado', 'Activa')->first();

        if (!$gestionActiva) {
            return redirect()->back()
                ->with('mensaje', 'No hay gestiones activas, no se puede actualizar el examen.')
                ->with('icono', 'error');
        }

        $request->validate([
            'nro_examen' => [
                'required',
                'string',
                'max:50',
                Rule::unique('examenes', 'nro_examen')->ignore($id),
            ],
            'fecha'      => 'required|date',
            'ponderacion'=> 'required|numeric|min:0|max:1',
        ], [
            'nro_examen.unique' => 'El número de examen ya está en uso.',
        ]);

        $examen = Examen::findOrFail($id);
        $examen->update([
            'nro_examen'  => $request->nro_examen,
            'fecha'       => $request->fecha,
            'ponderacion' => $request->ponderacion,
            'gestion_id'  => $gestionActiva->id,
        ]);

        BitacoraService::registrar('Actualizó examen: ' . $examen->nro_examen);

        return redirect()->route('admin.examenes.index')
            ->with('mensaje', 'Examen actualizado exitosamente.')
            ->with('icono', 'success');
    }

    /**
     * Eliminar examen.
     */
    public function destroy($id)
    {
        $examen = Examen::findOrFail($id);
        $examen->delete();

        BitacoraService::registrar('Eliminó examen: ' . $examen->nro_examen);

        return redirect()->route('admin.examenes.index')
            ->with('mensaje', 'Examen eliminado exitosamente.')
            ->with('icono', 'success');
    }
}
