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
    Schema::create('incidentes', function (Blueprint $table) {
        $table->uuid('id')->primary(); // Seguimos tu estándar de UUID
        $table->string('titulo'); // Ejemplo: "Desvío detectado"
        $table->text('descripcion'); // Detalle del incidente
        $table->decimal('latitud', 10, 8);
        $table->decimal('longitud', 11, 8);
        $table->enum('tipo', ['desvio', 'accidente', 'trafico', 'clima'])->default('desvio');
        $table->boolean('activo')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidentes');
    }
};
