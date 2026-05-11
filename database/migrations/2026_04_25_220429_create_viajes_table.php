<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('viajes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ruta_id');
            $table->uuid('vehiculo_id');
            $table->uuid('conductor_id');
            
            $table->timestamp('fecha_programada');
            $table->timestamp('fecha_inicio_real')->nullable();
            $table->timestamp('fecha_fin_real')->nullable();
            
            // Definimos el enum y el índice una sola vez aquí
            $table->enum('estado', ['programado', 'en_curso', 'completado', 'cancelado'])
                  ->default('programado')
                  ->index();

            $table->integer('pasajeros_actuales')->default(0);
            $table->timestamps();

            // Relaciones
            $table->foreign('ruta_id')->references('id')->on('rutas')->onDelete('cascade');
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos')->onDelete('cascade');
            $table->foreign('conductor_id')->references('id')->on('users')->onDelete('cascade');

            // Índices necesarios (Sin repetir el de estado)
            $table->index('ruta_id');
            $table->index('vehiculo_id');
            $table->index('fecha_programada');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('viajes');
    }
};