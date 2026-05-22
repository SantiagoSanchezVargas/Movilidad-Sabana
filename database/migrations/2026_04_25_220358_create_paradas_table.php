
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
        Schema::create('paradas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ruta_id');
            $table->foreign('ruta_id')->references('id')->on('rutas')->onDelete('cascade');
            
            $table->string('nombre')->comment('Nombre de la parada');
            $table->decimal('latitud', 10, 8)->comment('Coordenada de latitud');
            $table->decimal('longitud', 11, 8)->comment('Coordenada de longitud');
            
            $table->integer('orden')->comment('Orden en que aparece en la ruta (1, 2, 3...)');
            $table->time('hora_estimada')->nullable()->comment('Hora estimada de llegada');
            $table->text('descripcion')->nullable()->comment('Descripción de la parada');
            
            $table->timestamps();
            
            $table->index('ruta_id');
            $table->index('orden');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paradas');
    }
};