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
    Schema::table('users', function (Blueprint $table) {
        // Solo creamos 'apellido' si no existe
        if (!Schema::hasColumn('users', 'apellido')) {
            $table->string('apellido')->nullable()->after('name');
        }
        
        // Creamos 'role_id' para la relación con la tabla roles
        if (!Schema::hasColumn('users', 'role_id')) {
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
        }

        // Si existe una columna vieja llamada 'role' (string), la borramos para no confundir
        if (Schema::hasColumn('users', 'role')) {
            $table->dropColumn('role');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
