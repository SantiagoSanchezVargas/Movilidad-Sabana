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
    Schema::table('paradas', function (Blueprint $table) {
        // El orden es fundamental para trazar la ruta correctamente
        $table->integer('orden')->default(0)->after('nombre');
    });
}

public function down(): void
{
    Schema::table('paradas', function (Blueprint $table) {
        $table->dropColumn('orden');
    });
}
};
