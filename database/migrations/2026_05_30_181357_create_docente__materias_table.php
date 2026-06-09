<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('docente_materia', function (Blueprint $table) {
            $table->id();

            // Relación con docentes
            $table->string('codigo_docente');
            $table->foreign('codigo_docente')->references('codigo')->on('docentes')->onDelete('cascade');

            // Relación con materias
            $table->unsignedBigInteger('materia_id');
            $table->foreign('materia_id')->references('id')->on('materias')->onDelete('cascade');

            // Estado de contratación (ejemplo: contratado/baja)
            $table->enum('estado', ['activo', 'baja'])->default('activo');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('docente_materia');
    }
};
