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

            // Relación con docentes usando su código como FK
            $table->string('docente_codigo');
            $table->foreign('docente_codigo')->references('codigo')->on('docentes')->onDelete('cascade');

            // Solo guardamos los nombres
            $table->string('nombre_titulo');
            $table->string('nombre_maestria');
            $table->string('nombre_diplomado');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requisitos_docente');
    }
};

