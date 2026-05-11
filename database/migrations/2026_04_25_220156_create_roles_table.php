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
        // Tabla de roles
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre')->unique();
            $table->string('descripcion')->nullable();
            $table->json('permisos')->nullable();
            $table->timestamps();
        });

        // Tabla pivote corregida para UUID
        Schema::create('usuario_roles', function (Blueprint $table) {
            // USAMOS foreignUuid para que coincida con el ID de users y roles
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('role_id')->constrained('roles')->onDelete('cascade');
            
            $table->uuid('assigned_by')->nullable(); // Quién asignó el rol
            $table->timestamp('assigned_at')->useCurrent();
            
            // Clave primaria compuesta
            $table->primary(['user_id', 'role_id']);
            
            // Foreign key para el auditor
            $table->foreign('assigned_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
            
            // Índices para velocidad
            $table->index('assigned_at');
        });
    }

    /**
     * Revertir la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_roles');
        Schema::dropIfExists('roles');
    }
};