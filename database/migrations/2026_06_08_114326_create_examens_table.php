<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('examenes', function (Blueprint $table) {
            $table->id(); // PK auto incremental

            // Número de examen (puede ser correlativo o código)
            $table->string('nro_examen')->unique();

            // Fecha del examen
            $table->date('fecha');

            // Ponderación (ej: 0.25, 0.50, 1.00)
            $table->decimal('ponderacion', 4, 2);

            // Relación con gestión (tabla: gestions)
            $table->unsignedBigInteger('gestion_id');
            $table->foreign('gestion_id')->references('id')->on('gestions')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examenes');
    }
};
