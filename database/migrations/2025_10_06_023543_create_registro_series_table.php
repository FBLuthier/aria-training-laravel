<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registro_series', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rutina_ejercicio')->constrained('rutina_ejercicios')->onDelete('cascade');
            $table->foreignId('id_unidad_medida')->constrained('unidades_medida');
            $table->integer('numero_serie');
            $table->decimal('valor_registrado', 10, 2);
            $table->integer('repeticiones_realizadas');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registro_series');
    }
};