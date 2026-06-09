<?php

namespace Database\Seeders;

use App\Models\Administrativo;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Docente;
use App\Models\Gestion;
use App\Models\Modalidad;
use App\Models\Carrera;
use App\Models\Materia;
use App\Models\Turno;
use App\Models\Aula;
use App\Models\Horario;
use App\Models\Examen;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario Administrador
        $admin = User::create([
            'name'     => 'Yuliana Molina',
            'email'    => 'yuliadmin@admin',
            'password' => Hash::make('12345678'),
        ]);
        $admin->assignRole('ADMINISTRADOR');

        // Usuario Administrativo
        $administrativo = User::create([
            'name'     => 'Juan Pérez',
            'email'    => 'juan@admin',
            'password' => Hash::make('12345678'),
        ]);
        $administrativo->assignRole('ADMINISTRATIVO');

        // Usuario Docente
        $docente = User::create([
            'name'     => 'María López',
            'email'    => 'maria@admin',
            'password' => Hash::make('12345678'),
        ]);
        $docente->assignRole('DOCENTE');

       

        // Datos de Gestión
        Gestion::create(['semestre'=> 1,'año'=> 2024,'estado'=> 'Cerrada','created_at'=> now(),'updated_at'=> now(),]);
        Gestion::create(['semestre'=> 2,'año'=> 2024,'estado'=> 'Cerrada','created_at'=> now(),'updated_at'=> now(),]);
        Gestion::create(['semestre'=> 1,'año'=> 2025,'estado'=> 'Cerrada','created_at'=> now(),'updated_at'=> now(),]);
        Gestion::create(['semestre'=> 2,'año'=> 2025,'estado'=> 'Cerrada','created_at'=> now(),'updated_at'=> now(),]);
        Gestion::create(['semestre'=> 1,'año'=> 2026,'estado'=> 'Activa','created_at'=> now(),'updated_at'=> now(),]);

        //Datos para Modalidad
        Modalidad::create(['nombre' => 'Presencial', 'created_at' => now(), 'updated_at' => now()]);
        Modalidad::create(['nombre' => 'Virtual', 'created_at' => now(), 'updated_at' => now()]);

        //Datos para Carrera
        Carrera::create(['sigla'=> 'ROB187-6','nombre'=> 'Robótica','cupos_disponibles'=> 300,'created_at'=> now(),'updated_at'=> now()]);
        Carrera::create(['sigla'=> 'SIS187-2','nombre'=> 'Ingeniería en Sistemas','cupos_disponibles'=> 300,'created_at'=> now(),'updated_at'=> now()]);
        Carrera::create(['sigla'=> 'INFO187-3','nombre'=> 'Ingeniería Informática','cupos_disponibles'=> 300,'created_at'=> now(),'updated_at'=> now()]);
        Carrera::create(['sigla'=> 'RED187-5','nombre'=> 'Ingeniería en Redes y Telecomunicaciones','cupos_disponibles'=> 300,'created_at'=> now(),'updated_at'=> now()]);  
        
        //Datos para materias
        Materia::create(['nombre'=> 'Computación','ponderacion'=> 0.25,'created_at'=> now(),'updated_at'=> now(),]);
        Materia::create(['nombre'=> 'Matemáticas','ponderacion'=> 0.25,'created_at'=> now(),'updated_at'=> now(),]);
        Materia::create(['nombre'=> 'Física','ponderacion'=> 0.25,'created_at'=> now(),'updated_at'=> now(),]);
        Materia::create(['nombre'=> 'Inglés','ponderacion'=> 0.25,'created_at'=> now(),'updated_at'=> now(),]);

        //Datos para Turnos
        Turno::create(['nombre'=> 'Mañana','created_at'=> now(),'updated_at'=> now(),]);
        Turno::create(['nombre'=> 'Tarde','created_at'=> now(),'updated_at'=> now(),]);
        Turno::create(['nombre'=> 'Noche','created_at'=> now(),'updated_at'=> now(),]);

        // Datos para Horarios
        Horario::create(['turno_id'=> 1,'hora_inicio'=> '07:00','hora_fin'=> '08:10','created_at'=> now(),'updated_at'=> now(),]);
        Horario::create(['turno_id'=> 1,'hora_inicio'=> '08:10','hora_fin'=> '09:20','created_at'=> now(),'updated_at'=> now(),]);
        Horario::create(['turno_id'=> 1,'hora_inicio'=> '09:20','hora_fin'=> '09:35','created_at'=> now(),'updated_at'=> now(),]); // Receso
        Horario::create(['turno_id'=> 1,'hora_inicio'=> '09:35','hora_fin'=> '10:45','created_at'=> now(),'updated_at'=> now(),]);
        Horario::create(['turno_id'=> 1,'hora_inicio'=> '10:45','hora_fin'=> '11:55','created_at'=> now(),'updated_at'=> now(),]);
        
        Horario::create(['turno_id'=> 2,'hora_inicio'=> '13:00','hora_fin'=> '14:10','created_at'=> now(),'updated_at'=> now(),]);
        Horario::create(['turno_id'=> 2,'hora_inicio'=> '14:10','hora_fin'=> '15:20','created_at'=> now(),'updated_at'=> now(),]);
        Horario::create(['turno_id'=> 2,'hora_inicio'=> '15:20','hora_fin'=> '15:35','created_at'=> now(),'updated_at'=> now(),]); // Receso
        Horario::create(['turno_id'=> 2,'hora_inicio'=> '15:35','hora_fin'=> '16:45','created_at'=> now(),'updated_at'=> now(),]);
        Horario::create(['turno_id'=> 2,'hora_inicio'=> '16:45','hora_fin'=> '17:55','created_at'=> now(),'updated_at'=> now(),]);
        
        Horario::create(['turno_id'=> 3,'hora_inicio'=> '18:00','hora_fin'=> '19:10','created_at'=> now(),'updated_at'=> now(),]);
        Horario::create(['turno_id'=> 3,'hora_inicio'=> '19:10','hora_fin'=> '20:20','created_at'=> now(),'updated_at'=> now(),]);
        Horario::create(['turno_id'=> 3,'hora_inicio'=> '20:20','hora_fin'=> '20:35','created_at'=> now(),'updated_at'=> now(),]); // Receso
        Horario::create(['turno_id'=> 3,'hora_inicio'=> '20:35','hora_fin'=> '21:45','created_at'=> now(),'updated_at'=> now(),]);
        Horario::create(['turno_id'=> 3,'hora_inicio'=> '21:45','hora_fin'=> '22:55','created_at'=> now(),'updated_at'=> now(),]);


        //Datos para Aulas
        Aula::create(['nro_aula'=> '11','modulo'=> 236, 'piso'=> 1, 'created_at'=> now(),'updated_at'=> now(),]);
        Aula::create(['nro_aula'=> '12','modulo'=> 236, 'piso'=> 1, 'created_at'=> now(),'updated_at'=> now(),]);
        Aula::create(['nro_aula'=> '13','modulo'=> 236, 'piso'=> 1, 'created_at'=> now(),'updated_at'=> now(),]);
        Aula::create(['nro_aula'=> '14','modulo'=> 236, 'piso'=> 1, 'created_at'=> now(),'updated_at'=> now(),]);
        Aula::create(['nro_aula'=> '15','modulo'=> 236, 'piso'=> 1, 'created_at'=> now(),'updated_at'=> now(),]);
        Aula::create(['nro_aula'=> '21','modulo'=> 236, 'piso'=> 2, 'created_at'=> now(),'updated_at'=> now(),]);
        Aula::create(['nro_aula'=> '22','modulo'=> 236, 'piso'=> 2, 'created_at'=> now(),'updated_at'=> now(),]);
        Aula::create(['nro_aula'=> '23','modulo'=> 236, 'piso'=> 2, 'created_at'=> now(),'updated_at'=> now(),]);
        Aula::create(['nro_aula'=> '24','modulo'=> 236, 'piso'=> 2, 'created_at'=> now(),'updated_at'=> now(),]);
        Aula::create(['nro_aula'=> '25','modulo'=> 236, 'piso'=> 2, 'created_at'=> now(),'updated_at'=> now(),]);
        Aula::create(['nro_aula'=> '31','modulo'=> 236, 'piso'=> 3, 'created_at'=> now(),'updated_at'=> now(),]);
        Aula::create(['nro_aula'=> '32','modulo'=> 236, 'piso'=> 3, 'created_at'=> now(),'updated_at'=> now(),]);
        Aula::create(['nro_aula'=> '33','modulo'=> 236, 'piso'=> 3, 'created_at'=> now(),'updated_at'=> now(),]);
        Aula::create(['nro_aula'=> '34','modulo'=> 236, 'piso'=> 3, 'created_at'=> now(),'updated_at'=> now(),]);
        Aula::create(['nro_aula'=> '35','modulo'=> 236, 'piso'=> 3, 'created_at'=> now(),'updated_at'=> now(),]);
        
        // Datos para Examenes
        Examen::create(['nro_examen'=> '1', 'fecha'=> '2024-07-15', 'ponderacion'=> 0.30, 'gestion_id' => 5, 'created_at'=> now(),'updated_at'=> now(),]);
        Examen::create(['nro_examen'=> '2', 'fecha'=> '2024-08-15', 'ponderacion'=> 0.30, 'gestion_id' => 5, 'created_at'=> now(),'updated_at'=> now(),]);
        Examen::create(['nro_examen'=> '3', 'fecha'=> '2024-09-15', 'ponderacion'=> 0.40, 'gestion_id' => 5, 'created_at'=> now(),'updated_at'=> now(),]);
    }
    
}
