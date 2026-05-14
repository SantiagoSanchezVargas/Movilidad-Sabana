<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rutas', function (Blueprint $table) {

            // ID
            $table->uuid('id')->primary();

            // Usuario creador/conductor
            $table->foreignUuid('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Datos principales
            $table->string('nombre'); // Ej: Ruta Sabana Centro Directo
            $table->string('codigo')->unique(); // Ej: RS-001

            // Información descriptiva
            $table->text('descripcion')->nullable();

            // Datos operativos
            $table->decimal('distancia_km', 8, 2)->nullable();

            // Mejor string porque manejas textos tipo "40 min"
            $table->string('duracion_estimada')->nullable();

            // SOLO texto descriptivo
            $table->string('origen')->nullable();
            $table->string('destino')->nullable();

            // Para polilíneas u otros datos
            $table->json('parametros_ruta')->nullable();

            // Estado
            $table->enum('estado', [
                'activo',
                'inactivo',
                'mantenimiento'
            ])->default('activo');

            // Fechas
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rutas');
    }
};