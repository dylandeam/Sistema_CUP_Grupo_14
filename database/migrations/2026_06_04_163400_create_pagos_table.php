<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();

            // Atributos principales
            $table->decimal('monto', 10, 2); // monto con 2 decimales
            $table->date('fecha');           // fecha del pago
            $table->enum('estado', ['PENDIENTE', 'CONFIRMADO'])->default('PENDIENTE');
            $table->string('comprobante')->nullable(); // puede ser nombre de archivo o código

            // Relación con inscripción
            $table->unsignedBigInteger('inscripcion_id');
            $table->foreign('inscripcion_id')->references('id')->on('inscripcions')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};

