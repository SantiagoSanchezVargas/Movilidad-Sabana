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
        $table->uuid('conductor_id')->nullable()->after('vehiculo_id');
        $table->foreign('conductor_id')->references('id')->on('conductores')->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('rutas', function (Blueprint $table) {
        $table->dropForeign(['conductor_id']);
        $table->dropColumn('conductor_id');
    });
}
};
