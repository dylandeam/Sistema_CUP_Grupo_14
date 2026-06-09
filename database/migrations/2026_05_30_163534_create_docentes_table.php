<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('docentes', function (Blueprint $table) {
            // Relación con users
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');

            // Datos principales
            $table->string('codigo')->primary(); // Código generado automáticamente como PK
            $table->string('nombre');
            $table->string('apellido');
            $table->string('ci')->unique(); // CI único
            $table->date('fecha_nacimiento');
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->string('foto')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('docentes');
    }
};

