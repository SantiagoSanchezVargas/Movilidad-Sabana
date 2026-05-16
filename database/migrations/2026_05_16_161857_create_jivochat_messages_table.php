<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jivochat_messages', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id')->nullable(); // ✅ CAMBIADO A UUID
            $table->string('sender_name');
            $table->string('sender_phone')->nullable();
            $table->text('message');
            $table->string('channel')->default('jivochat');
            $table->string('status')->default('received');
            $table->timestamps();
            
            // Agregar foreign key DESPUÉS
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jivochat_messages');
    }
};