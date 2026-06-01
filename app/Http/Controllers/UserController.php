<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Administrativo;
use App\Models\Docente;
use App\Services\BitacoraService;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['roles', 'administrativo', 'docente'])->get();

        foreach ($users as $user) {
            if ($user->hasRole('ADMINISTRATIVO') && $user->administrativo) {
                $user->codigo   = $user->administrativo->codigo;
                $user->nombre   = $user->administrativo->nombre;
                $user->apellido = $user->administrativo->apellido;
                $user->ci       = $user->administrativo->ci;
                $user->telefono = $user->administrativo->telefono;
            } elseif ($user->hasRole('DOCENTE') && $user->docente) {
                $user->codigo   = $user->docente->codigo;
                $user->nombre   = $user->docente->nombre;
                $user->apellido = $user->docente->apellido;
                $user->ci       = $user->docente->ci;
                $user->telefono = $user->docente->telefono;
            } else {
                // Para ADMINISTRADOR o POSTULANTE se usan los datos básicos de users
                $user->codigo   = $user->codigo;
                $user->nombre   = $user->name; // importante: usar name
                $user->apellido = $user->apellido;
                $user->ci       = $user->ci;
                $user->telefono = $user->telefono;
            }
        }

        return view('admin.RegistrarUsuarios.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.RegistrarUsuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'rol'      => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name'     => Str::before($data['email'], '@'),
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $user->assignRole($data['rol']);

        if ($data['rol'] === 'ADMINISTRATIVO') {
            Administrativo::create([
                'usuario_id'      => $user->id,
                'codigo'          => $this->generateCodigo('ADMIN', $user->id),
                'nombre'          => null,
                'apellido'        => null,
                'ci'              => null,
                'fecha_nacimiento'=> null,
                'telefono'        => null,
                'direccion'       => null,
                'cargo'           => null,
                'foto'            => null,
            ]);
        } elseif ($data['rol'] === 'DOCENTE') {
            Docente::create([
                'usuario_id'      => $user->id,
                'codigo'          => $this->generateCodigo('DOC', $user->id),
                'nombre'          => null,
                'apellido'        => null,
                'ci'              => null,
                'fecha_nacimiento'=> null,
                'telefono'        => null,
                'direccion'       => null,
                'foto'            => null,
            ]);
        }

        BitacoraService::registrar('Creó usuario: ' . $user->email . ' con rol ' . $data['rol']);

        return redirect()->route('admin.users.index')
            ->with('mensaje', 'Usuario creado correctamente.')
            ->with('icon', 'success');
    }

    public function show($id)
    {
        $user = User::with(['roles', 'administrativo', 'docente'])->findOrFail($id);
        return view('admin.RegistrarUsuarios.show', compact('user'));
    }

    public function edit($id)
    {
        $user  = User::with(['roles', 'administrativo', 'docente'])->findOrFail($id);
        $roles = Role::all();
        return view('admin.RegistrarUsuarios.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'email'    => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'rol'      => 'required|exists:roles,name',
        ]);

        $user->update([
            'name'  => Str::before($data['email'], '@'),
            'email' => $data['email'],
        ]);

        if (!empty($data['password'])) {
            $user->update(['password' => bcrypt($data['password'])]);
        }

        $user->syncRoles([$data['rol']]);

        // Actualizar tabla correspondiente
        if ($data['rol'] === 'ADMINISTRATIVO') {
            $admin = Administrativo::where('usuario_id', $user->id)->first();
            if ($admin) {
                $admin->update([
                    'codigo'          => $admin->codigo ?? $this->generateCodigo('ADMIN', $user->id),
                    'nombre'          => $admin->nombre,
                    'apellido'        => $admin->apellido,
                    'ci'              => $admin->ci,
                    'fecha_nacimiento'=> $admin->fecha_nacimiento,
                    'telefono'        => $admin->telefono,
                    'direccion'       => $admin->direccion,
                    'cargo'           => $admin->cargo,
                ]);
            } else {
                Administrativo::create([
                    'usuario_id'      => $user->id,
                    'codigo'          => $this->generateCodigo('ADMIN', $user->id),
                    'nombre'          => null,
                    'apellido'        => null,
                    'ci'              => null,
                    'fecha_nacimiento'=> null,
                    'telefono'        => null,
                    'direccion'       => null,
                    'cargo'           => null,
                    'foto'            => null,
                ]);
            }
        } elseif ($data['rol'] === 'DOCENTE') {
            $doc = Docente::where('usuario_id', $user->id)->first();
            if ($doc) {
                $doc->update([
                    'codigo'          => $doc->codigo ?? $this->generateCodigo('DOC', $user->id),
                    'nombre'          => $doc->nombre,
                    'apellido'        => $doc->apellido,
                    'ci'              => $doc->ci,
                    'fecha_nacimiento'=> $doc->fecha_nacimiento,
                    'telefono'        => $doc->telefono,
                    'direccion'       => $doc->direccion,
                    'foto'            => $doc->foto,
                ]);
            } else {
                Docente::create([
                    'usuario_id'      => $user->id,
                    'codigo'          => $this->generateCodigo('DOC', $user->id),
                    'nombre'          => null,
                    'apellido'        => null,
                    'ci'              => null,
                    'fecha_nacimiento'=> null,
                    'telefono'        => null,
                    'direccion'       => null,
                    'foto'            => null,
                ]);
            }
        }

        BitacoraService::registrar('Actualizó usuario: ' . $user->email . ' con rol ' . $data['rol']);

        return redirect()->route('admin.users.index')
            ->with('mensaje', 'Usuario actualizado.')
            ->with('icon', 'success');
    }

    private function generateCodigo(string $prefix, int $userId): string
    {
        return strtoupper($prefix . '-' . str_pad($userId, 6, '0', STR_PAD_LEFT));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        Administrativo::where('usuario_id', $user->id)->delete();
        Docente::where('usuario_id', $user->id)->delete();

        $user->delete();

        BitacoraService::registrar('Eliminó usuario: ' . $user->email);

        return redirect()->route('admin.users.index')
            ->with('mensaje', 'Usuario eliminado.')
            ->with('icon', 'success');
    }
}
