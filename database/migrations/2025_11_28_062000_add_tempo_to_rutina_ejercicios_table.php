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
            if (!Schema::hasColumn('rutina_ejercicios', 'tempo')) {
                $table->json('tempo')->nullable()->after('descanso_segundos');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rutina_ejercicios', function (Blueprint $table) {
            if (Schema::hasColumn('rutina_ejercicios', 'tempo')) {
                $table->dropColumn('tempo');
            }
        });
    }
};
