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
        Permission::create(['name' => 'admin.materias.edit'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.materias.update'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.materias.destroy'])->assignRole($administrador, $administrativo);

    

        //Permisos para Aulas
        Permission::create(['name' => 'admin.aulas.index'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.aulas.create'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.aulas.store'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.aulas.edit'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.aulas.update'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.aulas.destroy'])->assignRole($administrador, $administrativo);

    

        //Permisos para Horarios
        Permission::create(['name' => 'admin.horarios.index'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.horarios.create'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.horarios.store'])->assignRole($administrador, $administrativo);
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
        Permission::create(['name' => 'admin.administrativos.import'])->assignRole($administrador, $administrativo);

        //Permisos para Docentes 
        Permission::create(['name' => 'admin.docentes.index'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.docentes.create'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.docentes.store'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.docentes.show'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.docentes.edit'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.docentes.update'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.docentes.destroy'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.docentes.import'])->assignRole($administrador, $administrativo);
        
       
        // Permisos para cambiar contraseña
        Permission::create(['name' => 'password.change'])->assignRole($administrador, $administrativo, $docente);
        Permission::create(['name' => 'password.update'])->assignRole($administrador, $administrativo, $docente);

        //Permisos para Bitacora
        Permission::create(['name' => 'admin.bitacora.index'])->assignRole($administrador);
    

        // Permisos para Turnos
        Permission::create(['name' => 'admin.turnos.index'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.turnos.create'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.turnos.store'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.turnos.edit'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.turnos.update'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.turnos.destroy'])->assignRole($administrador, $administrativo);

        // Permisos para Modalidades
        Permission::create(['name' => 'admin.modalidades.index'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.modalidades.create'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.modalidades.store'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.modalidades.edit'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.modalidades.update'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.modalidades.destroy'])->assignRole($administrador, $administrativo);

        // Permisos para Postulantes
        Permission::create(['name' => 'admin.postulantes.index'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.postulantes.create'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.postulantes.store'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.postulantes.show'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.postulantes.edit'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.postulantes.update'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.postulantes.import'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.postulantes.destroy'])->assignRole($administrador, $administrativo);

        // Permisos para Inscripciones
        Permission::create(['name' => 'admin.inscripciones.index'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.inscripciones.create'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.inscripciones.store'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.inscripciones.show'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.inscripciones.edit'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.inscripciones.update'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.inscripciones.destroy'])->assignRole($administrador, $administrativo);

        // Permisos para Pagos
        Permission::create(['name' => 'admin.pagos.index'])->assignRole($administrador, $administrativo);
            

        // Permisos para Grupos
        Permission::create(['name' => 'admin.grupos.index'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.grupos.store'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.grupos.horariosgrupos'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.grupos.showhorario'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.grupos.show'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.grupos.edit'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.grupos.update'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.grupos.destroy'])->assignRole($administrador, $administrativo);


        // Permisos para Carga Horaria
        Permission::create(['name' => 'admin.carga_horaria.index'])->assignRole($administrador, $administrativo, $docente);
        Permission::create(['name' => 'admin.carga_horaria.show'])->assignRole($administrador, $administrativo, $docente);
       


        // Permisos para Exámenes
        Permission::create(['name' => 'admin.examenes.index'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.examenes.create'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.examenes.store'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.examenes.edit'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.examenes.update'])->assignRole($administrador, $administrativo);
        Permission::create(['name' => 'admin.examenes.destroy'])->assignRole($administrador, $administrativo);
    }

}

