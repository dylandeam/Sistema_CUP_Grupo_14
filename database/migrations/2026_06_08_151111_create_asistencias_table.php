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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id(); // PK

            $table->date('fecha'); // Fecha de la asistencia
            $table->string('estado', 20); // Ej: Presente, Falta, Licencia

            // Relaciones
            $table->string('codigo_postulante'); // debe ser string para coincidir con postulantes.codigo
            $table->unsignedBigInteger('id_grupo');

            $table->foreign('codigo_postulante')
                  ->references('codigo')
                  ->on('postulantes')
                  ->onDelete('cascade');

            $table->foreign('id_grupo')
                  ->references('id')
                  ->on('grupos')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
