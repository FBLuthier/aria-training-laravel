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
            $table->boolean('track_rpe')->default(false)->after('unidad_peso');
            $table->boolean('track_rir')->default(false)->after('track_rpe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rutina_ejercicios', function (Blueprint $table) {
            $table->dropColumn(['track_rpe', 'track_rir']);
        });
    }
};
