
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
        Schema::create('parada_confirmaciones', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('parada_id');
            $table->uuid('conductor_id');
            $table->uuid('ruta_id');
            
            $table->foreign('parada_id')->references('id')->on('paradas')->onDelete('cascade');
            $table->foreign('conductor_id')->references('id')->on('conductores')->onDelete('cascade');
            $table->foreign('ruta_id')->references('id')->on('rutas')->onDelete('cascade');
            
            $table->dateTime('confirmado_en')->nullable()->comment('Cuándo confirmó llegada');
            $table->decimal('latitud_confirmacion', 10, 8)->nullable()->comment('Lat real donde confirmó');
            $table->decimal('longitud_confirmacion', 11, 8)->nullable()->comment('Lng real donde confirmó');
            $table->integer('pasajeros_subieron')->nullable()->default(0)->comment('Cuántos pasajeros subieron');
            $table->string('estado')->default('pendiente')->comment('pendiente, confirmado, retrasado');
            
            $table->timestamps();
            
            $table->index('ruta_id');
            $table->index('conductor_id');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parada_confirmaciones');
    }
};