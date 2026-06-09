<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripcion_carrera', function (Blueprint $table) {
            $table->id();

            // Orden de preferencia (1ra opción, 2da opción)
            $table->integer('orden_pref');

            // Relación con inscripción
            $table->unsignedBigInteger('inscripcion_id');
            $table->foreign('inscripcion_id')->references('id')->on('inscripcions')->onDelete('cascade');

            // Relación con carrera
            $table->unsignedBigInteger('carrera_id');
            $table->foreign('carrera_id')->references('id')->on('carreras')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripcion_carrera');
    }
};
