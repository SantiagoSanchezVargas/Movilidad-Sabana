<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reportes_congestion', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('viaje_id')->nullable();
            
            // Definimos el enum e índice una sola vez
            $table->enum('tipo', ['trafico_pesado', 'accidente', 'obra_vial', 'desvio', 'otro'])
                  ->index();
            
            $table->text('descripcion')->nullable();
            $table->integer('nivel_severidad')->default(1); // 1-5
            
            $table->timestamps();

            // Relaciones
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('viaje_id')->references('id')->on('viajes')->onDelete('set null');

            // Índices necesarios
            $table->index('user_id');
            $table->index('created_at');
        });

        // Soporte PostGIS para la ubicación exacta del reporte
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE reportes_congestion ADD COLUMN ubicacion geometry(Point, 4326)');
            DB::statement('CREATE INDEX idx_reportes_ubicacion ON reportes_congestion USING GIST(ubicacion)');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS idx_reportes_ubicacion');
        }
        Schema::dropIfExists('reportes_congestion');
    }
};