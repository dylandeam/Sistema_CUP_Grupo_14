<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripcions', function (Blueprint $table) {
            $table->id();

            // Estado de inscripción (solo PENDIENTE o INSCRITO)
            $table->enum('estado', ['PENDIENTE', 'INSCRITO'])->default('PENDIENTE');

            // Fecha de inscripción
            $table->date('fecha_insc');

            // Costo de inscripción
            $table->decimal('costo', 10, 2)->default(0.00);

            // Relación con postulante (PK personalizada: codigo)
            $table->string('postulante_codigo');
            $table->foreign('postulante_codigo')->references('codigo')->on('postulantes')->onDelete('cascade');

            // Relación con gestión (tabla: gestions)
            $table->unsignedBigInteger('gestion_id');
            $table->foreign('gestion_id')->references('id')->on('gestions')->onDelete('cascade');

            // Relación con modalidad (tabla: modalidades)
            $table->unsignedBigInteger('modalidad_id');
            $table->foreign('modalidad_id')->references('id')->on('modalidades')->onDelete('cascade');

            // Relación con turno (tabla: turnos)
            $table->unsignedBigInteger('turno_id');
            $table->foreign('turno_id')->references('id')->on('turnos')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripcions');
    }
};
