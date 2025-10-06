<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ejercicios_x_grupo_muscular', function (Blueprint $table) {
            $table->foreignId('id_ejercicio')->constrained('ejercicios')->onDelete('cascade');
            $table->foreignId('id_grupo_muscular')->constrained('grupos_musculares')->onDelete('cascade');
            $table->primary(['id_ejercicio', 'id_grupo_muscular']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ejercicios_x_grupo_muscular');
    }
};