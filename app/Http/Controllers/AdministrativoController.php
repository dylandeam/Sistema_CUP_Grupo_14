<?php

namespace App\Http\Controllers;

use App\Models\Administrativo;
use App\Models\User;
use App\Services\BitacoraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class AdministrativoController extends Controller
{
    /**
     * Listado de administrativos
     */
    public function index()
    {
        $administrativos = Administrativo::with('user.roles')->get();
        return view('admin.administrativos.index', compact('administrativos'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.administrativos.create', compact('roles'));
    }

    /**
     * Guardar nuevo administrativo
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'          => 'required|string|max:255',
            'apellido'        => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'ci'              => 'required|unique:administrativos,ci',
            'fecha_nacimiento'=> 'required|date',
            'telefono'        => 'nullable|string|max:20',
            'direccion'       => 'nullable|string|max:255',
            'cargo'           => 'required|string|max:255',
            'rol'             => 'required',
            'foto'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'ci.unique' => 'El CI ya está registrado en otro administrativo.',
            'email.unique' => 'El correo ya está en uso.',
        ]);

        // Crear usuario
        $usuario = User::create([
            'name'     => $request->nombre . ' ' . $request->apellido,
            'email'    => $request->email,
            'password' => Hash::make($request->ci),
        ]);
        $usuario->assignRole($request->rol);

        // Generar código automático
        $codigo = strtoupper(substr($request->apellido,0,1)) .
                  strtoupper(substr($request->nombre,0,1)) .
                  substr($request->ci,0,4);

        // Procesar foto
        $fotoPath = $request->hasFile('foto')
            ? $request->file('foto')->store('administrativos', 'public')
            : null;

        // Crear administrativo
        Administrativo::create([
            'usuario_id'      => $usuario->id,
            'codigo'          => $codigo,
            'nombre'          => $request->nombre,
            'apellido'        => $request->apellido,
            'ci'              => $request->ci,
            'fecha_nacimiento'=> $request->fecha_nacimiento,
            'telefono'        => $request->telefono,
            'direccion'       => $request->direccion,
            'cargo'           => $request->cargo,
            'foto'            => $fotoPath,
        ]);

        BitacoraService::registrar('Creó administrativo: ' . $request->nombre . ' ' . $request->apellido);

        return redirect()->route('admin.administrativos.index')
            ->with('mensaje', 'Administrativo registrado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Mostrar un administrativo específico
     */
    public function show(Administrativo $administrativo)
    {
        return view('admin.administrativos.show', compact('administrativo'));
    }

    /**
     * Formulario de edición
     */
    public function edit(Administrativo $administrativo)
    {
        $roles = Role::all();
        return view('admin.administrativos.edit', compact('administrativo','roles'));
    }

    /**
     * Actualizar administrativo
     */
    public function update(Request $request, Administrativo $administrativo)
    {
        $request->validate([
            'nombre'          => 'required|string|max:255',
            'apellido'        => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email,' . $administrativo->usuario_id,
            'ci'              => 'required|unique:administrativos,ci,' . $administrativo->id,
            'fecha_nacimiento'=> 'required|date',
            'telefono'        => 'nullable|string|max:20',
            'direccion'       => 'nullable|string|max:255',
            'cargo'           => 'required|string|max:255',
            'rol'             => 'required',
            'foto'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'ci.unique' => 'El CI ya está registrado en otro administrativo.',
            'email.unique' => 'El correo ya está en uso.',
        ]);

        // Actualizar usuario
        $usuario = $administrativo->user;
        $usuario->update([
            'name'  => $request->nombre . ' ' . $request->apellido,
            'email' => $request->email,
        ]);
        $usuario->syncRoles([$request->rol]);

        // Regenerar código automático
        $codigo = strtoupper(substr($request->apellido,0,1)) .
                  strtoupper(substr($request->nombre,0,1)) .
                  substr($request->ci,0,4);

        // Procesar foto
        $fotoPath = $administrativo->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('administrativos', 'public');
        }

        // Actualizar administrativo
        $administrativo->update([
            'codigo'          => $codigo,
            'nombre'          => $request->nombre,
            'apellido'        => $request->apellido,
            'ci'              => $request->ci,
            'fecha_nacimiento'=> $request->fecha_nacimiento,
            'telefono'        => $request->telefono,
            'direccion'       => $request->direccion,
            'cargo'           => $request->cargo,
            'foto'            => $fotoPath,
        ]);

        BitacoraService::registrar('Actualizó administrativo: ' . $administrativo->codigo);

        return redirect()->route('admin.administrativos.index')
            ->with('mensaje', 'Administrativo actualizado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Eliminar administrativo
     */
    public function destroy(Administrativo $administrativo)
    {
        if ($administrativo->foto && Storage::disk('public')->exists($administrativo->foto)) {
            Storage::disk('public')->delete($administrativo->foto);
        }

        if ($administrativo->user) {
            $administrativo->user->delete();
        }

        $administrativo->delete();

        BitacoraService::registrar('Eliminó administrativo: ' . $administrativo->codigo);

        return redirect()->route('admin.administrativos.index')
            ->with('mensaje', 'Administrativo eliminado correctamente')
            ->with('icono', 'success');
    }
}
