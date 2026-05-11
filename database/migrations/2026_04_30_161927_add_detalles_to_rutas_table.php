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
    Schema::table('rutas', function (Blueprint $table) {
        // Añadimos lo que falta para la autenticidad del Nodo Chía
        $table->string('operador')->nullable()->after('nombre'); // Ej: Transvalvanera
        $table->string('color', 7)->default('#1e293b')->after('operador'); // Color hexadecimal
    });
}

public function down(): void
{
    Schema::table('rutas', function (Blueprint $table) {
        $table->dropColumn(['operador', 'color']);
    });
}
};
