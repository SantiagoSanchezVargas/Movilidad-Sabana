<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('placa')->unique();
            $table->string('modelo');
            $table->string('marca');
            $table->integer('capacidad');
            
            // Definimos el índice aquí una sola vez
            $table->enum('estado', ['activo', 'mantenimiento', 'fuera_servicio'])
                  ->default('activo')
                  ->index();

            $table->uuid('conductor_id')->nullable();
            $table->timestamps();

            // Relaciones
            $table->foreign('conductor_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            // Índices necesarios (Sin duplicar el de estado ni el de placa)
            $table->index('conductor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};