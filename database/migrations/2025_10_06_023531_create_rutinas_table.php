<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rutinas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 45);
            $table->foreignId('id_objetivo')->constrained('objetivos');
            $table->foreignId('id_usuario')->constrained('usuarios');
            $table->boolean('estado');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rutinas');
    }
};