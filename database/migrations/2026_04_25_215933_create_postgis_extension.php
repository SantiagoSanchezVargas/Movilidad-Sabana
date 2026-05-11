<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar la migración.
     * Crea la extensión PostGIS necesaria para soporte geoespacial
     */
    public function up(): void
    {
        // Habilitar extensión PostGIS (solo si es PostgreSQL)
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('CREATE EXTENSION IF NOT EXISTS postgis');
            DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
        }
    }

    /**
     * Revertir la migración.
     */
    public function down(): void
    {
        // No se recomienda desactivar extensiones en down()
        // ya que podrían romper datos existentes
    }
};
