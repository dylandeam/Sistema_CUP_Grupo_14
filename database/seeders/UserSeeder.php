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
        $admin = User::firstOrCreate(
            ['email' => 'yuliadmin@admin'],
            ['name' => 'Yuliana Molina', 'password' => Hash::make('12345678')]  // Admin mantiene contraseña fija
        );
        $admin->assignRole('ADMINISTRADOR');

        // Usuario Administrativo
        $administrativo = User::firstOrCreate(
            ['email' => 'juan@admin'],
            ['name' => 'Juan Pérez', 'password' => Hash::make('12345678')]  // Admin mantiene contraseña fija
        );
        $administrativo->assignRole('ADMINISTRATIVO');

        // Usuario Docente
        $docente = User::firstOrCreate(
            ['email' => 'maria@admin'],
            ['name' => 'María López', 'password' => Hash::make('12345678')]  // Admin mantiene contraseña fija
        );
        // Datos de Gestión
        Gestion::firstOrCreate(['semestre'=> 1,'año'=> 2024], ['estado'=> 'Cerrada','created_at'=> now(),'updated_at'=> now()]);
        Gestion::firstOrCreate(['semestre'=> 2,'año'=> 2024], ['estado'=> 'Cerrada','created_at'=> now(),'updated_at'=> now()]);
        Gestion::firstOrCreate(['semestre'=> 1,'año'=> 2025], ['estado'=> 'Cerrada','created_at'=> now(),'updated_at'=> now()]);
        Gestion::firstOrCreate(['semestre'=> 2,'año'=> 2025], ['estado'=> 'Cerrada','created_at'=> now(),'updated_at'=> now()]);
        Gestion::firstOrCreate(['semestre'=> 1,'año'=> 2026], ['estado'=> 'Activa','created_at'=> now(),'updated_at'=> now()]);

        //Datos para Modalidad
        Modalidad::firstOrCreate(['nombre' => 'Presencial'], ['created_at' => now(), 'updated_at' => now()]);
        Modalidad::firstOrCreate(['nombre' => 'Virtual'], ['created_at' => now(), 'updated_at' => now()]);

        //Datos para Carrera
        Carrera::firstOrCreate(['sigla'=> 'ROB187-6'], ['nombre'=> 'Robótica','cupos_disponibles'=> 300,'created_at'=> now(),'updated_at'=> now()]);
        Carrera::firstOrCreate(['sigla'=> 'SIS187-2'], ['nombre'=> 'Ingeniería en Sistemas','cupos_disponibles'=> 300,'created_at'=> now(),'updated_at'=> now()]);
        Carrera::firstOrCreate(['sigla'=> 'INFO187-3'], ['nombre'=> 'Ingeniería Informática','cupos_disponibles'=> 300,'created_at'=> now(),'updated_at'=> now()]);
        Carrera::firstOrCreate(['sigla'=> 'RED187-5'], ['nombre'=> 'Ingeniería en Redes y Telecomunicaciones','cupos_disponibles'=> 300,'created_at'=> now(),'updated_at'=> now()]);
        
        //Datos para materias
        Materia::firstOrCreate(['nombre'=> 'Computación'], ['ponderacion'=> 0.25,'created_at'=> now(),'updated_at'=> now()]);
        Materia::firstOrCreate(['nombre'=> 'Matemática'], ['ponderacion'=> 0.25,'created_at'=> now(),'updated_at'=> now()]);
        Materia::firstOrCreate(['nombre'=> 'Física'], ['ponderacion'=> 0.25,'created_at'=> now(),'updated_at'=> now()]);
        Materia::firstOrCreate(['nombre'=> 'Inglés'], ['ponderacion'=> 0.25,'created_at'=> now(),'updated_at'=> now()]);

        //Datos para Turnos
        Turno::firstOrCreate(['nombre'=> 'Mañana'], ['created_at'=> now(),'updated_at'=> now()]);
        Turno::firstOrCreate(['nombre'=> 'Tarde'], ['created_at'=> now(),'updated_at'=> now()]);
        Turno::firstOrCreate(['nombre'=> 'Noche'], ['created_at'=> now(),'updated_at'=> now()]);

        // Datos para Horarios
        Horario::firstOrCreate(['turno_id'=> 1,'hora_inicio'=> '07:00','hora_fin'=> '08:10'], ['created_at'=> now(),'updated_at'=> now()]);
        Horario::firstOrCreate(['turno_id'=> 1,'hora_inicio'=> '08:10','hora_fin'=> '09:20'], ['created_at'=> now(),'updated_at'=> now()]);
        Horario::firstOrCreate(['turno_id'=> 1,'hora_inicio'=> '09:20','hora_fin'=> '09:35'], ['created_at'=> now(),'updated_at'=> now()]); // Receso
        Horario::firstOrCreate(['turno_id'=> 1,'hora_inicio'=> '09:35','hora_fin'=> '10:45'], ['created_at'=> now(),'updated_at'=> now()]);
        Horario::firstOrCreate(['turno_id'=> 1,'hora_inicio'=> '10:45','hora_fin'=> '11:55'], ['created_at'=> now(),'updated_at'=> now()]);
        
        Horario::firstOrCreate(['turno_id'=> 2,'hora_inicio'=> '13:00','hora_fin'=> '14:10'], ['created_at'=> now(),'updated_at'=> now()]);
        Horario::firstOrCreate(['turno_id'=> 2,'hora_inicio'=> '14:10','hora_fin'=> '15:20'], ['created_at'=> now(),'updated_at'=> now()]);
        Horario::firstOrCreate(['turno_id'=> 2,'hora_inicio'=> '15:20','hora_fin'=> '15:35'], ['created_at'=> now(),'updated_at'=> now()]); // Receso
        Horario::firstOrCreate(['turno_id'=> 2,'hora_inicio'=> '15:35','hora_fin'=> '16:45'], ['created_at'=> now(),'updated_at'=> now()]);
        Horario::firstOrCreate(['turno_id'=> 2,'hora_inicio'=> '16:45','hora_fin'=> '17:55'], ['created_at'=> now(),'updated_at'=> now()]);
        
        Horario::firstOrCreate(['turno_id'=> 3,'hora_inicio'=> '18:00','hora_fin'=> '19:10'], ['created_at'=> now(),'updated_at'=> now()]);
        Horario::firstOrCreate(['turno_id'=> 3,'hora_inicio'=> '19:10','hora_fin'=> '20:20'], ['created_at'=> now(),'updated_at'=> now()]);
        Horario::firstOrCreate(['turno_id'=> 3,'hora_inicio'=> '20:20','hora_fin'=> '20:35'], ['created_at'=> now(),'updated_at'=> now()]); // Receso
        Horario::firstOrCreate(['turno_id'=> 3,'hora_inicio'=> '20:35','hora_fin'=> '21:45'], ['created_at'=> now(),'updated_at'=> now()]);
        Horario::firstOrCreate(['turno_id'=> 3,'hora_inicio'=> '21:45','hora_fin'=> '22:55'], ['created_at'=> now(),'updated_at'=> now()]);


        //Datos para Aulas
        Aula::firstOrCreate(['nro_aula'=> '11','modulo'=> 236, 'piso'=> 1], ['created_at'=> now(),'updated_at'=> now()]);
        Aula::firstOrCreate(['nro_aula'=> '12','modulo'=> 236, 'piso'=> 1], ['created_at'=> now(),'updated_at'=> now()]);
        Aula::firstOrCreate(['nro_aula'=> '13','modulo'=> 236, 'piso'=> 1], ['created_at'=> now(),'updated_at'=> now()]);
        Aula::firstOrCreate(['nro_aula'=> '14','modulo'=> 236, 'piso'=> 1], ['created_at'=> now(),'updated_at'=> now()]);
        Aula::firstOrCreate(['nro_aula'=> '15','modulo'=> 236, 'piso'=> 1], ['created_at'=> now(),'updated_at'=> now()]);
        Aula::firstOrCreate(['nro_aula'=> '21','modulo'=> 236, 'piso'=> 2], ['created_at'=> now(),'updated_at'=> now()]);
        Aula::firstOrCreate(['nro_aula'=> '22','modulo'=> 236, 'piso'=> 2], ['created_at'=> now(),'updated_at'=> now()]);
        Aula::firstOrCreate(['nro_aula'=> '23','modulo'=> 236, 'piso'=> 2], ['created_at'=> now(),'updated_at'=> now()]);
        Aula::firstOrCreate(['nro_aula'=> '24','modulo'=> 236, 'piso'=> 2], ['created_at'=> now(),'updated_at'=> now()]);
        Aula::firstOrCreate(['nro_aula'=> '25','modulo'=> 236, 'piso'=> 2], ['created_at'=> now(),'updated_at'=> now()]);
        Aula::firstOrCreate(['nro_aula'=> '31','modulo'=> 236, 'piso'=> 3], ['created_at'=> now(),'updated_at'=> now()]);
        Aula::firstOrCreate(['nro_aula'=> '32','modulo'=> 236, 'piso'=> 3], ['created_at'=> now(),'updated_at'=> now()]);
        Aula::firstOrCreate(['nro_aula'=> '33','modulo'=> 236, 'piso'=> 3], ['created_at'=> now(),'updated_at'=> now()]);
        Aula::firstOrCreate(['nro_aula'=> '34','modulo'=> 236, 'piso'=> 3], ['created_at'=> now(),'updated_at'=> now()]);
        Aula::firstOrCreate(['nro_aula'=> '35','modulo'=> 236, 'piso'=> 3], ['created_at'=> now(),'updated_at'=> now()]);
        
        // Datos para Examenes
        Examen::firstOrCreate(['nro_examen'=> '1', 'gestion_id' => 5], ['fecha'=> '2024-07-15', 'ponderacion'=> 0.30, 'created_at'=> now(),'updated_at'=> now()]);
        Examen::firstOrCreate(['nro_examen'=> '2', 'gestion_id' => 5], ['fecha'=> '2024-08-15', 'ponderacion'=> 0.30, 'created_at'=> now(),'updated_at'=> now()]);
        Examen::firstOrCreate(['nro_examen'=> '3', 'gestion_id' => 5], ['fecha'=> '2024-09-15', 'ponderacion'=> 0.40, 'created_at'=> now(),'updated_at'=> now()]);
    }
    
}
