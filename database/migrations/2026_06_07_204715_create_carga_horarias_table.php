<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCargaHorariasTable extends Migration
{
    public function up()
    {
        Schema::create('carga_horarias', function (Blueprint $table) {
            $table->id();

            // Relación con docentes (usando codigo como PK)
            $table->string('docente_codigo');
            $table->foreign('docente_codigo')->references('codigo')->on('docentes')->onDelete('cascade');

            // Relación con materias
            $table->unsignedBigInteger('materia_id');
            $table->foreign('materia_id')->references('id')->on('materias')->onDelete('cascade');

            // Relación con grupos
            $table->unsignedBigInteger('grupo_id');
            $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete('cascade');

            // Relación con horarios
            $table->unsignedBigInteger('horario_id');
            $table->foreign('horario_id')->references('id')->on('horarios')->onDelete('cascade');

            // Relación con aulas (nullable porque puede ser virtual)
            $table->unsignedBigInteger('aula_id')->nullable();
            $table->foreign('aula_id')->references('id')->on('aulas')->onDelete('set null');

            // Relación con gestiones
            $table->unsignedBigInteger('gestion_id');
            $table->foreign('gestion_id')->references('id')->on('gestions')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carga_horarias');
    }
}
