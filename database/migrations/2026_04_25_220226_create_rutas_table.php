<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rutas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nombre'); // Ej: Ruta Sabana Centro Directo
            $table->string('codigo')->unique(); // Ej: RS-001
            $table->decimal('distancia_km', 8, 2);
            $table->integer('duracion_estimada'); // En minutos
            $table->json('parametros_ruta')->nullable(); // Para guardar polilíneas o datos extra
            $table->enum('estado', ['activa', 'inactiva', 'mantenimiento'])->default('activa');
            $table->timestamps();
        });

        // Soporte PostGIS
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE rutas ADD COLUMN origen geometry(Point, 4326)');
            DB::statement('ALTER TABLE rutas ADD COLUMN destino geometry(Point, 4326)');
            DB::statement('CREATE INDEX idx_rutas_origen ON rutas USING GIST(origen)');
            DB::statement('CREATE INDEX idx_rutas_destino ON rutas USING GIST(destino)');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS idx_rutas_destino');
            DB::statement('DROP INDEX IF EXISTS idx_rutas_origen');
        }
        Schema::dropIfExists('rutas');
    }
};