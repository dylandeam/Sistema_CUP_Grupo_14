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
        Schema::create('resultados', function (Blueprint $table) {
            $table->id();
            $table->string('estado'); // puedes cambiar a enum si lo prefieres
            $table->decimal('promedio_examen', 5, 2);

            // Foreign keys
            $table->unsignedBigInteger('id_inscripcion');
            $table->unsignedBigInteger('id_inscripcion_carrera')->nullable();

            $table->foreign('id_inscripcion')
                  ->references('id')->on('inscripcions')
                  ->onDelete('cascade');

            $table->foreign('id_inscripcion_carrera')
                  ->references('id')->on('inscripcion_carrera')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultados');
    }
};
