<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requisitos_docente', function (Blueprint $table) {
            $table->id();

            // Relación con docentes
            $table->unsignedBigInteger('docente_id');
            $table->foreign('docente_id')->references('id')->on('docentes')->onDelete('cascade');

            // Requisitos principales
            $table->boolean('titulo')->default(false);
            $table->string('nombre_titulo')->nullable();

            $table->boolean('maestria')->default(false);
            $table->string('nombre_maestria')->nullable();

            $table->boolean('diplomado')->default(false);
            $table->string('nombre_diplomado')->nullable();

            // Área de especialidad (matemáticas, computación, física, inglés)
            $table->string('area_especialidad')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requisitos_docente');
    }
};

