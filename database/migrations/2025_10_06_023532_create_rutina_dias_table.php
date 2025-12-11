<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rutina_dias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rutina_id')->constrained('rutinas')->onDelete('cascade');
            $table->tinyInteger('numero_dia');
            $table->string('nombre_dia', 30);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rutina_dias');
    }
};
