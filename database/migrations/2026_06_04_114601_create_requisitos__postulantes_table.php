<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requisitos_postulantes', function (Blueprint $table) {
            $table->id();

            // Relación con postulantes usando su código como FK
            $table->string('postulante_codigo');
            $table->foreign('postulante_codigo')->references('codigo')->on('postulantes')->onDelete('cascade');

            // Requisitos como booleanos (checkbox V/F)
            $table->boolean('fotocopia_ci')->default(false);
            $table->boolean('certificado_nacimiento')->default(false);
            $table->boolean('titulo_bachiller')->default(false);
            $table->boolean('libreta_colegio')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requisitos_postulantes');
    }
};


