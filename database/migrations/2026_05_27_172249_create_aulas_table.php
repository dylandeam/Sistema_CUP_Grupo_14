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
        Schema::create('aulas', function (Blueprint $table) {
            $table->id();

            $table->integer('nro_aula');       // número de aula
            $table->string('modulo', 100);     // módulo o bloque
            $table->integer('piso');           // piso del edificio

            $table->timestamps();

            // Evitar duplicados de aula dentro del mismo módulo y piso
            $table->unique(['nro_aula', 'modulo', 'piso']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};

