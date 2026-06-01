<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Services\BitacoraService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HorarioController extends Controller
{
    /**
     * Mostrar listado de horarios.
     */
    public function index()
    {
        $horarios = Horario::all();
        return view('admin.horarios.index', compact('horarios'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        return view('admin.horarios.create');
    }

    /**
     * Guardar nuevo horario.
     */
    public function store(Request $request)
    {
        $request->validate([
            'hora_inicio' => 'required',
            'hora_fin'    => [
                'required',
                'after:hora_inicio',
                Rule::unique('horarios')->where(function ($query) use ($request) {
                    return $query->where('hora_inicio', $request->hora_inicio);
                }),
            ],
        ], [
            'hora_inicio.required' => 'La hora de inicio es obligatoria.',
            'hora_fin.required' => 'La hora de fin es obligatoria.',
            'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'hora_fin.unique' => 'Ya existe un horario con esa hora de inicio y fin.',
        ]);

        Horario::create($request->only(['hora_inicio', 'hora_fin']));

        BitacoraService::registrar('Creó horario: ' . $request->hora_inicio . ' - ' . $request->hora_fin);

        return redirect()->route('admin.horarios.index')
            ->with('mensaje', 'Horario registrado exitosamente.')
            ->with('icono', 'success');
    }

    /**
     * Mostrar un horario específico.
     */
    public function show(Horario $horario)
    {
        return view('admin.horarios.show', compact('horario'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $horario = Horario::findOrFail($id);
        return view('admin.horarios.edit', compact('horario'));
    }

    /**
     * Actualizar horario.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'hora_inicio' => 'required',
            'hora_fin'    => [
                'required',
                'after:hora_inicio',
                Rule::unique('horarios')
                    ->where(function ($query) use ($request) {
                        return $query->where('hora_inicio', $request->hora_inicio);
                    })
                    ->ignore($id, 'id'),
            ],
        ], [
            'hora_inicio.required' => 'La hora de inicio es obligatoria.',
            'hora_fin.required' => 'La hora de fin es obligatoria.',
            'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'hora_fin.unique' => 'Ya existe un horario con esa hora de inicio y fin.',
        ]);

        $horario = Horario::findOrFail($id);
        $horario->update($request->only(['hora_inicio', 'hora_fin']));

        BitacoraService::registrar('Actualizó horario: ' . $horario->hora_inicio . ' - ' . $horario->hora_fin);

        return redirect()->route('admin.horarios.index')
            ->with('mensaje', 'Horario actualizado exitosamente.')
            ->with('icono', 'success');
    }

    /**
     * Eliminar horario.
     */
    public function destroy($id)
    {
        $horario = Horario::findOrFail($id);
        $horario->delete();

        BitacoraService::registrar('Eliminó horario: ' . $horario->hora_inicio . ' - ' . $horario->hora_fin);

        return redirect()->route('admin.horarios.index')
            ->with('mensaje', 'Horario eliminado exitosamente.')
            ->with('icono', 'success');
    }
}
