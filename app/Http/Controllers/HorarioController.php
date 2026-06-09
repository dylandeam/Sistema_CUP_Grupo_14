<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\Turno;
use App\Services\BitacoraService;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    /**
     * Mostrar listado de horarios.
     */
    public function index()
    {
        $horarios = Horario::with('turno')->get();
        return view('admin.horarios.index', compact('horarios'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        $turnos = Turno::all();
        return view('admin.horarios.create', compact('turnos'));
    }

    /**
     * Guardar nuevo horario.
     */
    public function store(Request $request)
    {
        $request->validate([
            'turno_id'   => 'required|exists:turnos,id',
            'hora_inicio' => 'required',
            'hora_fin'    => 'required|after:hora_inicio',
        ]);

        $turno = Turno::find($request->turno_id);

        // Validación de rangos según turno (todos 5 horas)
        if ($turno->nombre === 'Mañana' &&
            ($request->hora_inicio < '07:00' || $request->hora_fin > '12:00')) {
            return back()->withErrors(['hora_inicio' => 'El horario debe estar entre 07:00 y 12:00 para el turno Mañana.']);
        }

        if ($turno->nombre === 'Tarde' &&
            ($request->hora_inicio < '13:00' || $request->hora_fin > '18:00')) {
            return back()->withErrors(['hora_inicio' => 'El horario debe estar entre 13:00 y 18:00 para el turno Tarde.']);
        }

        if ($turno->nombre === 'Noche' &&
            ($request->hora_inicio < '18:00' || $request->hora_fin > '23:00')) {
            return back()->withErrors(['hora_inicio' => 'El horario debe estar entre 18:00 y 23:00 para el turno Noche.']);
        }

        // Validación de unicidad dentro del turno
        $existe = Horario::where('turno_id', $request->turno_id)
            ->where('hora_inicio', $request->hora_inicio)
            ->where('hora_fin', $request->hora_fin)
            ->exists();

        if ($existe) {
            return back()->withErrors(['hora_inicio' => 'Ya existe un horario con esas horas en este turno.']);
        }

        Horario::create($request->only(['turno_id', 'hora_inicio', 'hora_fin']));

        BitacoraService::registrar('Creó horario en turno ' . $turno->nombre . ': ' . $request->hora_inicio . ' - ' . $request->hora_fin);

        return redirect()->route('admin.horarios.index')
            ->with('mensaje', 'Horario registrado exitosamente.')
            ->with('icono', 'success');
    }


    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $horario = Horario::findOrFail($id);
        $turnos = Turno::all();
        return view('admin.horarios.edit', compact('horario', 'turnos'));
    }

    /**
     * Actualizar horario.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'turno_id'   => 'required|exists:turnos,id',
            'hora_inicio' => 'required',
            'hora_fin'    => 'required|after:hora_inicio',
        ]);

        $turno = Turno::find($request->turno_id);

        // Validación de rangos según turno (todos 5 horas)
        if ($turno->nombre === 'Mañana' &&
            ($request->hora_inicio < '07:00' || $request->hora_fin > '12:00')) {
            return back()->withErrors(['hora_inicio' => 'El horario debe estar entre 07:00 y 12:00 para el turno Mañana.']);
        }

        if ($turno->nombre === 'Tarde' &&
            ($request->hora_inicio < '13:00' || $request->hora_fin > '18:00')) {
            return back()->withErrors(['hora_inicio' => 'El horario debe estar entre 13:00 y 18:00 para el turno Tarde.']);
        }

        if ($turno->nombre === 'Noche' &&
            ($request->hora_inicio < '18:00' || $request->hora_fin > '23:00')) {
            return back()->withErrors(['hora_inicio' => 'El horario debe estar entre 18:00 y 23:00 para el turno Noche.']);
        }

        // Validación de unicidad dentro del turno (ignorando el mismo registro)
        $existe = Horario::where('turno_id', $request->turno_id)
            ->where('hora_inicio', $request->hora_inicio)
            ->where('hora_fin', $request->hora_fin)
            ->where('id', '!=', $id)
            ->exists();

        if ($existe) {
            return back()->withErrors(['hora_inicio' => 'Ya existe un horario con esas horas en este turno.']);
        }

        $horario = Horario::findOrFail($id);
        $horario->update($request->only(['turno_id', 'hora_inicio', 'hora_fin']));

        BitacoraService::registrar('Actualizó horario en turno ' . $turno->nombre . ': ' . $horario->hora_inicio . ' - ' . $horario->hora_fin);

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

        BitacoraService::registrar('Eliminó horario en turno ' . $horario->turno_id . ': ' . $horario->hora_inicio . ' - ' . $horario->hora_fin);

        return redirect()->route('admin.horarios.index')
            ->with('mensaje', 'Horario eliminado exitosamente.')
            ->with('icono', 'success');
    }
}
