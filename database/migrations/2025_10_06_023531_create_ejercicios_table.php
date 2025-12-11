<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ejercicios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 45);
            $table->text('descripcion')->nullable();
            $table->foreignId('equipo_id')->constrained('equipos');
            $table->foreignId('grupo_muscular_id')->constrained('grupos_musculares');
            $table->boolean('estado');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ejercicios');
    }
};
