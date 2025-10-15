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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->string('action'); // create, update, delete, restore, force_delete
            $table->string('model_type'); // App\Models\Equipo
            $table->unsignedBigInteger('model_id');
            $table->json('old_values')->nullable(); // Valores anteriores para updates
            $table->json('new_values')->nullable(); // Valores nuevos para creates/updates
            $table->string('ip_address', 45)->nullable(); // Soporte para IPv6
            $table->text('user_agent')->nullable();
            $table->timestamps();

            // Índices para mejorar el rendimiento de consultas
            $table->index(['model_type', 'model_id']);
            $table->index(['user_id']);
            $table->index(['action']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
