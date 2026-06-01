<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
    }
}
