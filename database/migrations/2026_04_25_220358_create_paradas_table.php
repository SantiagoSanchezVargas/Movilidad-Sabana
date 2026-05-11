<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar la migración.
     * Crea tabla de paradas con tipos y geometría PostGIS
     */
    public function up(): void
    {
        Schema::create('paradas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ruta_id');
            $table->unsignedSmallInteger('numero_orden');
            
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            
            // Ajustado para coincidir con el controlador
            $table->enum('tipo_parada', ['salida', 'intermedia', 'destino'])
                  ->default('intermedia');

            // Columnas físicas para lat/lng (Evitan el error "Undefined column")
            $table->decimal('lat', 10, 8);
            $table->decimal('lng', 11, 8);
            
            $table->decimal('tarifa_desde_origen', 10, 2)->default(0);
            $table->decimal('tarifa_hacia_destino', 10, 2)->default(0);
            $table->float('radio_metros')->default(300);
            
            $table->timestamps();
            
            $table->foreign('ruta_id')
                  ->references('id')
                  ->on('rutas')
                  ->onDelete('cascade');

            $table->index('ruta_id');
            $table->unique(['ruta_id', 'numero_orden']);
        });

        // Columna PostGIS para cálculos espaciales
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE paradas ADD COLUMN ubicacion geometry(Point, 4326)');
            DB::statement('CREATE INDEX idx_paradas_ubicacion ON paradas USING GIST(ubicacion)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('paradas');
    }
};