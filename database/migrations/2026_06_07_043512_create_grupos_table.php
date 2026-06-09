<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGruposTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grupos', function (Blueprint $table) {
            $table->id(); // PK

            $table->string('nombre');
            $table->integer('cupos');

            // Relaciones (FK)
            $table->unsignedBigInteger('id_gestion');
            $table->unsignedBigInteger('id_modalidad');
            $table->unsignedBigInteger('id_turno');
            $table->unsignedBigInteger('id_aula')->nullable();
           
            // Claves foráneas
            $table->foreign('id_gestion')->references('id')->on('gestions')->onDelete('cascade');
            $table->foreign('id_modalidad')->references('id')->on('modalidades')->onDelete('cascade');
            $table->foreign('id_turno')->references('id')->on('turnos')->onDelete('cascade');
            $table->foreign('id_aula')->references('id')->on('aulas')->onDelete('cascade');
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
}
