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
        $administrador = Role::firstOrCreate(['name' => 'ADMINISTRADOR', 'guard_name' => 'web']);
        $administrativo = Role::firstOrCreate(['name' => 'ADMINISTRATIVO', 'guard_name' => 'web']);
        $docente        = Role::firstOrCreate(['name' => 'DOCENTE', 'guard_name' => 'web']);
        $postulante     = Role::firstOrCreate(['name' => 'POSTULANTE', 'guard_name' => 'web']);

        // Crear permisos y asignarlos

        //Permisos para Gestiones
        Permission::firstOrCreate(['name' => 'admin.gestiones.index', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.gestiones.create', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.gestiones.store', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.gestiones.show', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.gestiones.edit', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.gestiones.update', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.gestiones.destroy', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);


        //Permisos para Carreras
        Permission::firstOrCreate(['name' => 'admin.carreras.index', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.carreras.create', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.carreras.store', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.carreras.show', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.carreras.edit', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.carreras.update', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.carreras.destroy', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);

    

        //Permisos para Materia
        Permission::firstOrCreate(['name' => 'admin.materias.index', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.materias.create', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.materias.store', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.materias.edit', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.materias.update', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.materias.destroy', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);

    

        //Permisos para Aulas
        Permission::firstOrCreate(['name' => 'admin.aulas.index', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.aulas.create', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.aulas.store', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.aulas.edit', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.aulas.update', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.aulas.destroy', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);

    

        //Permisos para Horarios
        Permission::firstOrCreate(['name' => 'admin.horarios.index', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.horarios.create', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.horarios.store', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.horarios.edit', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.horarios.update', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.horarios.destroy', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);

    

        //Permisos para Roles
        Permission::firstOrCreate(['name' => 'admin.roles.index', 'guard_name' => 'web'])->assignRole($administrador);
        Permission::firstOrCreate(['name' => 'admin.roles.create', 'guard_name' => 'web'])->assignRole($administrador);
        Permission::firstOrCreate(['name' => 'admin.roles.store', 'guard_name' => 'web'])->assignRole($administrador);
        Permission::firstOrCreate(['name' => 'admin.roles.permisos', 'guard_name' => 'web'])->assignRole($administrador);
        Permission::firstOrCreate(['name' => 'admin.roles.edit', 'guard_name' => 'web'])->assignRole($administrador);
        Permission::firstOrCreate(['name' => 'admin.roles.update', 'guard_name' => 'web'])->assignRole($administrador);
        Permission::firstOrCreate(['name' => 'admin.roles.destroy', 'guard_name' => 'web'])->assignRole($administrador);


        //Permisos para Administrativos
        Permission::firstOrCreate(['name' => 'admin.administrativos.index', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.administrativos.create', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.administrativos.store', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.administrativos.show', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.administrativos.edit', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.administrativos.update', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.administrativos.destroy', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.administrativos.import', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);

        //Permisos para Docentes 
        Permission::firstOrCreate(['name' => 'admin.docentes.index', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.docentes.create', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.docentes.store', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.docentes.show', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.docentes.edit', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.docentes.update', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.docentes.destroy', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.docentes.import', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        
       
        // Permisos para cambiar contraseña
        Permission::firstOrCreate(['name' => 'password.change', 'guard_name' => 'web'])->assignRole($administrador, $administrativo, $docente);
        Permission::firstOrCreate(['name' => 'password.update', 'guard_name' => 'web'])->assignRole($administrador, $administrativo, $docente);

        //Permisos para Bitacora
        Permission::firstOrCreate(['name' => 'admin.bitacora.index', 'guard_name' => 'web'])->assignRole($administrador);
    

        // Permisos para Turnos
        Permission::firstOrCreate(['name' => 'admin.turnos.index', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.turnos.create', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.turnos.store', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.turnos.edit', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.turnos.update', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.turnos.destroy', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);

        // Permisos para Modalidades
        Permission::firstOrCreate(['name' => 'admin.modalidades.index', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.modalidades.create', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.modalidades.store', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.modalidades.edit', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.modalidades.update', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.modalidades.destroy', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);

        // Permisos para Postulantes
        Permission::firstOrCreate(['name' => 'admin.postulantes.index', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.postulantes.create', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.postulantes.store', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.postulantes.show', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.postulantes.edit', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.postulantes.update', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.postulantes.import', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.postulantes.destroy', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);

        // Permisos para Inscripciones
        Permission::firstOrCreate(['name' => 'admin.inscripciones.index', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.inscripciones.create', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.inscripciones.store', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.inscripciones.show', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.inscripciones.edit', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.inscripciones.update', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.inscripciones.destroy', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);

        // Permisos para Pagos
        Permission::firstOrCreate(['name' => 'admin.pagos.index', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
            

        // Permisos para Grupos
        Permission::firstOrCreate(['name' => 'admin.grupos.index', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.grupos.store', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.grupos.horariosgrupos', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.grupos.showhorario', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.grupos.show', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.grupos.edit', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.grupos.update', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.grupos.destroy', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);


        // Permisos para Carga Horaria
        Permission::firstOrCreate(['name' => 'admin.carga_horaria.index', 'guard_name' => 'web'])->assignRole($administrador, $administrativo, $docente);
        Permission::firstOrCreate(['name' => 'admin.carga_horaria.show', 'guard_name' => 'web'])->assignRole($administrador, $administrativo, $docente);
       


        // Permisos para Exámenes
        Permission::firstOrCreate(['name' => 'admin.examenes.index', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.examenes.create', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.examenes.store', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.examenes.edit', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.examenes.update', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.examenes.destroy', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);

        // Permisos para Notas de Exámenes
        Permission::firstOrCreate(['name' => 'admin.notas_examen.index', 'guard_name' => 'web'])->assignRole($administrador, $administrativo, $docente);
        Permission::firstOrCreate(['name' => 'admin.notas_examen.create', 'guard_name' => 'web'])->assignRole($administrador, $administrativo, $docente);
        Permission::firstOrCreate(['name' => 'admin.notas_examen.store', 'guard_name' => 'web'])->assignRole($administrador, $administrativo, $docente);
        Permission::firstOrCreate(['name' => 'admin.notas_examen.edit', 'guard_name' => 'web'])->assignRole($administrador, $administrativo, $docente);
        Permission::firstOrCreate(['name' => 'admin.notas_examen.update', 'guard_name' => 'web'])->assignRole($administrador, $administrativo, $docente);
        Permission::firstOrCreate(['name' => 'admin.notas_examen.destroy', 'guard_name' => 'web'])->assignRole($administrador, $administrativo, $docente);
        Permission::firstOrCreate(['name' => 'admin.notas_examen.inscritos', 'guard_name' => 'web'])->assignRole($administrador, $administrativo, $docente);


        // Permisos para Asistencias
        Permission::firstOrCreate(['name' => 'admin.asistencias.index', 'guard_name' => 'web'])->assignRole($administrador, $administrativo, $docente);
        Permission::firstOrCreate(['name' => 'admin.asistencias.create', 'guard_name' => 'web'])->assignRole($administrador, $administrativo, $docente);
        Permission::firstOrCreate(['name' => 'admin.asistencias.store', 'guard_name' => 'web'])->assignRole($administrador, $administrativo, $docente);
        Permission::firstOrCreate(['name' => 'admin.asistencias.edit', 'guard_name' => 'web'])->assignRole($administrador, $administrativo, $docente);
        Permission::firstOrCreate(['name' => 'admin.asistencias.update', 'guard_name' => 'web'])->assignRole($administrador, $administrativo, $docente);
        Permission::firstOrCreate(['name' => 'admin.asistencias.destroy', 'guard_name' => 'web'])->assignRole($administrador, $administrativo, $docente);

        // Permisos para Postulantes en Grupos
        Permission::firstOrCreate(['name' => 'admin.postulante_grupos.index', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.postulante_grupos.show', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);

        // Permisos para Promedios de Examen
        Permission::firstOrCreate(['name' => 'admin.promedios_examen.index', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.promedios_examen.show', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);

        // Permisos para Resultados Finales
        Permission::firstOrCreate(['name' => 'admin.resultados.index', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.resultados.generar', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.resultados.admitidos', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.resultados.no_admitidos', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);

        // Permisos para Reportes
        Permission::firstOrCreate(['name' => 'admin.reportes.index', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
        Permission::firstOrCreate(['name' => 'admin.reportes.export', 'guard_name' => 'web'])->assignRole($administrador, $administrativo);
    }

}

