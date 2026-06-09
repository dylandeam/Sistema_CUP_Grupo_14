<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('postulantes', function (Blueprint $table) {
            $table->string('codigo')->primary(); // Código como PK

            // Relación con users
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');

            // Datos principales
            $table->string('nombre');
            $table->string('apellidos');
            $table->string('ci')->unique(); // CI único
            $table->date('fecha_nacimiento');
            $table->enum('sexo', ['M','F']); // obligatorio
            $table->string('telefono')->nullable();
            $table->string('direccion'); // obligatorio
            $table->string('colegio');   // obligatorio
            $table->string('ciudad');    // obligatorio
            $table->string('foto')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('postulantes');
    }
};
