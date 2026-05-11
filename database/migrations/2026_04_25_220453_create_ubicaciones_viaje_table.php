<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar la migración.
     * Crea tabla de ubicaciones en tiempo real para tracking
     * Nota: Esta tabla crece rápidamente. Considerar particionamiento en producción
     */
    public function up(): void
    {
        Schema::create('ubicaciones_viaje', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('viaje_id');
            
            // Datos de ubicación
            $table->float('latitud');
            $table->float('longitud');
            $table->float('velocidad_kmh')->nullable();
            $table->float('rumbo')->nullable(); // 0-360 grados
            $table->unsignedSmallInteger('precision_metros')->nullable();
            
            // Datos del dispositivo
            $table->float('temperatura_cpu')->nullable();
            $table->unsignedSmallInteger('bateria_porcentaje')->nullable();
            $table->enum('estado_conexion', ['wifi', '4g', '3g', 'offline'])->nullable();
            
            // Timestamp de creación (crucial para ordenamiento)
            $table->timestamp('registrado_en')->useCurrent();
            
            // Foreign key
            $table->foreign('viaje_id')
                  ->references('id')
                  ->on('viajes')
                  ->onDelete('cascade');
            
            // Índices para consultas frecuentes
            $table->index('viaje_id');
            $table->index('registrado_en'); // Para rangos de tiempo
            $table->index(['viaje_id', 'registrado_en']);
        });

        // Crear columna de geometría PostGIS
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE ubicaciones_viaje ADD COLUMN punto_ubicacion geometry(Point, 4326)');
            // Índice espacial GIST para consultas de proximidad
            DB::statement('CREATE INDEX idx_ubicaciones_punto ON ubicaciones_viaje USING GIST(punto_ubicacion)');
            // Índice compuesto para queries comunes
            DB::statement('CREATE INDEX idx_ubicaciones_viaje_tiempo ON ubicaciones_viaje (viaje_id, registrado_en DESC)');
        }
    }

    /**
     * Revertir la migración.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS idx_ubicaciones_viaje_tiempo');
            DB::statement('DROP INDEX IF EXISTS idx_ubicaciones_punto');
        }
        Schema::dropIfExists('ubicaciones_viaje');
    }
};