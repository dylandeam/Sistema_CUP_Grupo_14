<?php

namespace App\Http\Controllers;

use App\Services\BitacoraService;
use Illuminate\Http\Request;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Models\Permission as ModelsPermission;
use Spatie\Permission\Models\Role;

class RolController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        Role::create(['name' => $request->name]);
        BitacoraService::registrar('Creó rol: ' . $request->name);

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Rol creado exitosamente.')
            ->with('icon', 'success');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
        ]);

        $role = Role::findOrFail($id);
        $role->update(['name' => $request->name]);

        BitacoraService::registrar('Actualizó rol: ' . $request->name);

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Rol actualizado exitosamente.')
            ->with('icon', 'success');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $roleName = $role->name;
        $role->delete();

        BitacoraService::registrar('Eliminó rol: ' . $roleName);
        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Rol eliminado exitosamente.')
            ->with('icon', 'success');
    }

    public function permisos($id)
    {
        $rol= Role::Find($id);
        $permisos = ModelsPermission::all()->groupBy(function ($permiso){
            $name = strtolower($permiso->name);

            if (str_contains($name, 'gestiones')) {
                return 'Gestiones';
            }

            if (str_contains($name, 'carreras')) {
                return 'Carreras';
            }

            if (str_contains($name, 'materias')) {
                return 'Materias';
            }

            if (str_contains($name, 'aulas')) {
                return 'Aulas';
            }

            if (str_contains($name, 'horarios')) {
                return 'Horarios';
            }

            if (str_contains($name, 'roles')) {
                return 'Roles';
            }

            if (str_contains($name, 'administrativos')) {
                return 'Administrativos';
            }

            if (str_contains($name, 'docentes')) {
                return 'Docentes';
            }

            if (str_contains($name, 'bitacora')) {
                return 'Bitácora';
            }

            if (str_contains($name, 'password')) {
                return 'Cambiar Contraseña';
            }

            if (str_contains($name, 'turnos')) {
                return 'Turnos';
            }

            if (str_contains($name, 'modalidades')) {
                return 'Modalidades';
            }

            if (str_contains($name, 'postulantes')) {
                return 'Postulantes';
            }

            if (str_contains($name, 'inscripciones')) {
                return 'Inscripciones';
            }

            if (str_contains($name, 'pagos')) {
                return 'Pagos';
            }

            if (str_contains($name, 'grupos')) {
                return 'Grupos';
            }

            if (str_contains($name, 'carga_horaria')) {
                return 'Carga Horaria';
            }

            if (str_contains($name, 'examenes')) {
                return 'Exámenes';
            }

            return 'Otros';

        });
        
        return view('admin.roles.permisos', compact('permisos', 'rol'));
    }

    public function actualizarPermisos(Request $request, $id)
    {
        $request->validate([
            'permisos' => 'array',
            'permisos.*' => 'integer|exists:permissions,id',
        ]);

        $rol = Role::findOrFail($id);
        $rol->syncPermissions($request->input('permisos', []));

        BitacoraService::registrar('Actualizó permisos de rol: ' . $rol->name);

        return redirect()->route('admin.roles.permisos', $rol->id)
            ->with('mensaje', 'Permisos actualizados correctamente.')
            ->with('icon', 'success');
    }
}

