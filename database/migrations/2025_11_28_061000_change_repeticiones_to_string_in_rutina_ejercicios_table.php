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
        Schema::table('rutina_ejercicios', function (Blueprint $table) {
            // Cambiamos la columna a string para permitir rangos (ej: "10-12", "AMRAP")
            $table->string('repeticiones', 50)->change();

            // También aseguramos que peso_sugerido sea string si no lo es (por si acaso)
            // Aunque el error fue en repeticiones, peso también suele necesitar texto ("20kg", "RPE 8")
            if (! Schema::hasColumn('rutina_ejercicios', 'peso_sugerido')) {
                $table->string('peso_sugerido', 50)->nullable()->after('repeticiones');
            } else {
                // Si existe, aseguramos que sea string (si era decimal/int)
                // $table->string('peso_sugerido', 50)->change();
                // Nota: No forzaremos cambio de peso_sugerido sin verificar su estado actual para evitar errores,
                // pero añadiremos la columna si falta, ya que el componente la usa.
            }

            // Verificamos si falta descanso_segundos (usado en el componente)
            if (! Schema::hasColumn('rutina_ejercicios', 'descanso_segundos')) {
                $table->integer('descanso_segundos')->nullable()->after('peso_sugerido');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rutina_ejercicios', function (Blueprint $table) {
            // Revertir es arriesgado si hay datos no numéricos, pero definimos el intento
            // $table->integer('repeticiones')->change();
        });
    }
};
