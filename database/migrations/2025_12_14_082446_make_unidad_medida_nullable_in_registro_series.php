<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Hace nullable la columna unidad_medida_id ya que el nuevo sistema
 * de workout session no la utiliza.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registro_series', function (Blueprint $table) {
            $table->unsignedBigInteger('unidad_medida_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('registro_series', function (Blueprint $table) {
            $table->unsignedBigInteger('unidad_medida_id')->nullable(false)->change();
        });
    }
};

