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
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('turno_id');

            // Campos básicos
            $table->time('hora_inicio');
            $table->time('hora_fin');

            $table->timestamps();

            $table->unique(['turno_id', 'hora_inicio', 'hora_fin']);
            // asegura que no se repitan horarios iguales por turno

            $table->foreign('turno_id')
                  ->references('id')
                  ->on('turnos')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
