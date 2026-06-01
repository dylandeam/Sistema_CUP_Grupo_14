<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('administrativos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('codigo')->unique(); // Código generado automáticamente
            $table->string('nombre');
            $table->string('apellido');
            $table->string('ci')->unique(); // CI único
            $table->date('fecha_nacimiento');
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->string('cargo');
            $table->string('foto')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('administrativos');
    }
};
