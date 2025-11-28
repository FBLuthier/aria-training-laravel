<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rutinas', function (Blueprint $table) {
            if (Schema::hasColumn('rutinas', 'objetivo_id')) {
                // Primero eliminamos la clave foránea, luego la columna
                // Intentamos eliminar la FK, si falla (porque no existe o nombre incorrecto) atrapamos excepción o asumimos que existe
                try {
                    $table->dropForeign(['objetivo_id']);
                } catch (\Exception $e) {
                    // Ignorar si la FK no existe
                }
                $table->dropColumn('objetivo_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rutinas', function (Blueprint $table) {
            $table->foreignId('objetivo_id')->constrained('objetivos');
        });
    }
};
