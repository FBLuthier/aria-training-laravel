<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega campo 'lado' a registro_series para ejercicios unilaterales.
 * 
 * Valores posibles: null (ejercicio normal), 'left', 'right'
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registro_series', function (Blueprint $table) {
            // Campo para lado del ejercicio (null = normal, 'left'/'right' = unilateral)
            $table->string('lado', 10)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('registro_series', function (Blueprint $table) {
            $table->dropColumn('lado');
        });
    }
};


