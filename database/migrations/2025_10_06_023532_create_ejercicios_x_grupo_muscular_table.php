<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ejercicios_x_grupo_muscular', function (Blueprint $table) {
            $table->foreignId('ejercicio_id')->constrained('ejercicios')->onDelete('cascade');
            $table->foreignId('grupo_muscular_id')->constrained('grupos_musculares')->onDelete('cascade');
            $table->primary(['ejercicio_id', 'grupo_muscular_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ejercicios_x_grupo_muscular');
    }
};
