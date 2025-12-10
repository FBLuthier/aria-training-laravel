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
            $table->boolean('is_unilateral')->default(false)->after('unidad_repeticiones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rutina_ejercicios', function (Blueprint $table) {
            $table->dropColumn('is_unilateral');
        });
    }
};
