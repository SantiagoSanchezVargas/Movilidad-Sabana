<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar la migración.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email')->unique();
            $table->string('telefono')->nullable();
            $table->string('documento')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->uuid('avatar_id')->nullable();
            
            // Definimos el enum y el índice en una sola línea
            $table->enum('estado', ['activo', 'suspendido', 'eliminado'])
                  ->default('activo')
                  ->index(); 

            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamp('fecha_ultimo_login')->nullable();
            $table->rememberToken();
            $table->timestamps();
            
            // Índices restantes (quitamos los duplicados de estado y email)
            $table->index('created_at');
        });
    }

    /**
     * Revertir la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};