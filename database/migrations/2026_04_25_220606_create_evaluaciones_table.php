<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar la migración.
     * Crea tabla de evaluaciones de paradas por usuarios
     */
    public function up(): void
    {
        Schema::create('evaluaciones_parada', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('parada_id');
            $table->uuid('usuario_id'); // Quien evalúa
            $table->uuid('viaje_id')->nullable(); // Contexto del viaje
            
            // Calificación
            $table->unsignedTinyInteger('calificacion'); // 1-5 estrellas
            $table->text('comentario')->nullable();
            
            // Datos específicos de la parada
            $table->unsignedSmallInteger('tiempo_espera_real')->nullable(); // minutos
            $table->json('condiciones_parada')->nullable(); // clima, seguridad, limpieza, etc.
            
            // Contexto adicional
            $table->boolean('seria_util_parada_intermedia')->nullable();
            $table->json('sugerencias')->nullable();
            
            // Auditoría
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('parada_id')
                  ->references('id')
                  ->on('paradas')
                  ->onDelete('cascade');
            
            $table->foreign('usuario_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('viaje_id')
                  ->references('id')
                  ->on('viajes')
                  ->onDelete('set null');
            
            // Índices
            $table->index('parada_id');
            $table->index('usuario_id');
            $table->index('calificacion');
            $table->index('created_at');
            // Evitar múltiples evaluaciones del mismo usuario en la misma parada
            $table->unique(['parada_id', 'usuario_id', 'viaje_id']);
        });

        // Tabla adicional: evaluaciones de viajes completos
        Schema::create('evaluaciones_viaje', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('viaje_id');
            $table->uuid('usuario_id'); // Pasajero
            
            // Calificación general
            $table->unsignedTinyInteger('calificacion_general'); // 1-5
            
            // Detalles de calificación
            $table->unsignedTinyInteger('puntaje_conductor')->nullable();
            $table->unsignedTinyInteger('puntaje_vehiculo')->nullable();
            $table->unsignedTinyInteger('puntaje_puntualidad')->nullable();
            $table->unsignedTinyInteger('puntaje_seguridad')->nullable();
            $table->unsignedTinyInteger('puntaje_comodidad')->nullable();
            
            // Feedback
            $table->text('comentario')->nullable();
            $table->json('problemas_reportados')->nullable();
            $table->json('aspectos_positivos')->nullable();
            
            // Recomendación
            $table->boolean('recomendaria_ruta')->nullable();
            
            // Auditoría
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('viaje_id')
                  ->references('id')
                  ->on('viajes')
                  ->onDelete('cascade');
            
            $table->foreign('usuario_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            // Índices
            $table->index('viaje_id');
            $table->index('usuario_id');
            $table->index('calificacion_general');
            $table->index('created_at');
            // Un usuario por viaje
            $table->unique(['viaje_id', 'usuario_id']);
        });
    }

    /**
     * Revertir la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluaciones_viaje');
        Schema::dropIfExists('evaluaciones_parada');
    }
};