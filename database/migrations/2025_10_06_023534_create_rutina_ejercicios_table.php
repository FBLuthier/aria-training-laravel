<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rutina_ejercicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rutina_dia_id')->constrained('rutina_dias')->onDelete('cascade');
            $table->foreignId('ejercicio_id')->constrained('ejercicios')->onDelete('cascade');
            $table->foreignId('bloque_id')->nullable()->constrained('bloques_ejercicios_dias')->onDelete('set null');
            $table->integer('series');
            $table->integer('repeticiones');
            $table->text('indicaciones')->nullable();
            $table->integer('orden_en_dia')->nullable();
            $table->integer('orden_en_bloque')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rutina_ejercicios');
    }
};