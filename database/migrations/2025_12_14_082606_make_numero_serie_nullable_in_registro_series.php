<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Hace nullable la columna numero_serie (nombre antiguo).
 * La nueva columna es serie_numero.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registro_series', function (Blueprint $table) {
            if (Schema::hasColumn('registro_series', 'numero_serie')) {
                $table->integer('numero_serie')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('registro_series', function (Blueprint $table) {
            if (Schema::hasColumn('registro_series', 'numero_serie')) {
                $table->integer('numero_serie')->nullable(false)->change();
            }
        });
    }
};
