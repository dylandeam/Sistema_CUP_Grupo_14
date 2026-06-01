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
    Schema::create('materias', function (Blueprint $table) {
        $table->id(); // crea 'id' como PK auto incremental

        $table->string('nombre')->unique();
        $table->decimal('ponderacion', 4, 2); // permite valores como 0.25, 0.50, 1.00
        
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carreras');
    }
};
