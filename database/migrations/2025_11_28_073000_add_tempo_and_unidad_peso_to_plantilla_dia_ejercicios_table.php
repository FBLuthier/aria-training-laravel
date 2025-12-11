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
        Schema::table('plantilla_dia_ejercicios', function (Blueprint $table) {
            if (! Schema::hasColumn('plantilla_dia_ejercicios', 'tempo')) {
                $table->json('tempo')->nullable()->after('descanso_segundos');
            }
            if (! Schema::hasColumn('plantilla_dia_ejercicios', 'unidad_peso')) {
                $table->enum('unidad_peso', ['kg', 'lbs'])->default('kg')->after('peso_sugerido');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plantilla_dia_ejercicios', function (Blueprint $table) {
            if (Schema::hasColumn('plantilla_dia_ejercicios', 'tempo')) {
                $table->dropColumn('tempo');
            }
            if (Schema::hasColumn('plantilla_dia_ejercicios', 'unidad_peso')) {
                $table->dropColumn('unidad_peso');
            }
        });
    }
};
