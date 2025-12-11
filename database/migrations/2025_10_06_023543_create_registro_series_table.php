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
            $table->foreignId('rutina_ejercicio_id')->constrained('rutina_ejercicios')->onDelete('cascade');
            $table->integer('serie_numero');
            $table->decimal('peso', 8, 2)->nullable();
            $table->integer('reps')->nullable();
            $table->decimal('rpe', 3, 1)->nullable();
            $table->integer('rir')->nullable();
            $table->text('notas')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registro_series');
    }
};
