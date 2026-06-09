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
        Schema::create('nota_examens', function (Blueprint $table) {
            $table->id(); // PK ID

            // Campos propios de la nota
            $table->decimal('nota_materia', 5, 2);     // Ej: 85.50
            $table->decimal('nota_ponderada', 5, 2);   // Ej: 21.37

            // Relaciones (FKs)
            $table->unsignedBigInteger('id_examen');
            $table->unsignedBigInteger('id_materia');
            $table->unsignedBigInteger('id_inscripcion');

            $table->foreign('id_examen')->references('id')->on('examenes')->onDelete('cascade');
            $table->foreign('id_materia')->references('id')->on('materias')->onDelete('cascade');
            $table->foreign('id_inscripcion')->references('id')->on('inscripcions')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota_examens');
    }
};

