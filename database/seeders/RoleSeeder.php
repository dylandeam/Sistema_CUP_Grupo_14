<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear roles
        $administrador = Role::create(['name' => 'ADMINISTRADOR']);
        $administrativo = Role::create(['name' => 'ADMINISTRATIVO']);
        $docente        = Role::create(['name' => 'DOCENTE']);
        $postulante     = Role::create(['name' => 'POSTULANTE']);

        // Crear permisos y asignarlos

        //Permisos para Gestiones
        Permission::create(['name' => 'admin.gestiones.index'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.gestiones.create'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.gestiones.store'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.gestiones.show'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.gestiones.edit'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.gestiones.update'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.gestiones.destroy'])->assignRole($administrador, $administrativo);


        //Permisos para Carreras
        Permission::create(['name' => 'admin.carreras.index'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.carreras.create'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.carreras.store'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.carreras.show'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.carreras.edit'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.carreras.update'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.carreras.destroy'])->assignRole($administrador, $administrativo);

    

        //Permisos para Materia
        Permission::create(['name' => 'admin.materias.index'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.materias.create'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.materias.store'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.materias.show'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.materias.edit'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.materias.update'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.materias.destroy'])->assignRole($administrador, $administrativo);

    

        //Permisos para Aulas
        Permission::create(['name' => 'admin.aulas.index'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.aulas.create'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.aulas.store'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.aulas.show'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.aulas.edit'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.aulas.update'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.aulas.destroy'])->assignRole($administrador, $administrativo);

    

        //Permisos para Horarios
        Permission::create(['name' => 'admin.horarios.index'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.horarios.create'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.horarios.store'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.horarios.show'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.horarios.edit'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.horarios.update'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.horarios.destroy'])->assignRole($administrador, $administrativo);

    

        //Permisos para Roles
        Permission::create(['name' => 'admin.roles.index'])->assignRole($administrador);
        Permission::create(['name' => 'admin.roles.create'])->assignRole($administrador);
        Permission::create(['name' => 'admin.roles.store'])->assignRole($administrador);
        Permission::create(['name' => 'admin.roles.permisos'])->assignRole($administrador);
        Permission::create(['name' => 'admin.roles.edit'])->assignRole($administrador);
        Permission::create(['name' => 'admin.roles.update'])->assignRole($administrador);
        Permission::create(['name' => 'admin.roles.destroy'])->assignRole($administrador);


        //Permisos para Administrativos
        Permission::create(['name' => 'admin.administrativos.index'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.administrativos.create'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.administrativos.store'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.administrativos.show'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.administrativos.edit'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.administrativos.update'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.administrativos.destroy'])->assignRole($administrador, $administrativo);


        //Permisos para Docentes 
        Permission::create(['name' => 'admin.docentes.index'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.docentes.create'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.docentes.store'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.docentes.show'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.docentes.edit'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.docentes.update'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.docentes.destroy'])->assignRole($administrador, $administrativo);

        
       
        // Permisos para cambiar contraseña
        Permission::create(['name' => 'password.change'])->assignRole($administrador, $administrativo, $docente);
        Permission::create(['name' => 'password.update'])->assignRole($administrador, $administrativo, $docente);

        //Permisos para Bitacora
        Permission::create(['name' => 'admin.bitacora.index'])->assignRole($administrador);
    

    }
}

