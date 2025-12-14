<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega columnas faltantes a registro_series para compatibilidad
 * con el nuevo sistema de tracking de series.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registro_series', function (Blueprint $table) {
            // Agregar solo columnas que no existen
            if (!Schema::hasColumn('registro_series', 'serie_numero')) {
                $table->integer('serie_numero')->default(1)->after('rutina_ejercicio_id');
            }
            if (!Schema::hasColumn('registro_series', 'peso')) {
                $table->decimal('peso', 8, 2)->nullable()->after('lado');
            }
            if (!Schema::hasColumn('registro_series', 'reps')) {
                $table->integer('reps')->nullable()->after('peso');
            }
            if (!Schema::hasColumn('registro_series', 'rpe')) {
                $table->decimal('rpe', 3, 1)->nullable()->after('reps');
            }
            if (!Schema::hasColumn('registro_series', 'rir')) {
                $table->integer('rir')->nullable()->after('rpe');
            }
            if (!Schema::hasColumn('registro_series', 'completed_at')) {
                $table->timestamp('completed_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('registro_series', function (Blueprint $table) {
            $columns = ['serie_numero', 'peso', 'reps', 'rpe', 'rir', 'completed_at'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('registro_series', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

