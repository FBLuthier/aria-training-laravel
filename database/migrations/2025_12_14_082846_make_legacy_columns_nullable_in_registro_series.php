<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Hace nullable todas las columnas del esquema antiguo de registro_series
 * que ya no se utilizan en el nuevo sistema de workout session.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registro_series', function (Blueprint $table) {
            // Columnas del esquema antiguo que deben ser nullable
            if (Schema::hasColumn('registro_series', 'valor_registrado')) {
                $table->decimal('valor_registrado', 10, 2)->nullable()->change();
            }
            if (Schema::hasColumn('registro_series', 'repeticiones_realizadas')) {
                $table->integer('repeticiones_realizadas')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        // No revertimos ya que los datos podrían ser null
    }
};
