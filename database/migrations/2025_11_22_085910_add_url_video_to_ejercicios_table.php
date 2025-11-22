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
        Schema::table('ejercicios', function (Blueprint $table) {
            $table->string('url_video')->nullable()->after('descripcion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ejercicios', function (Blueprint $table) {
            $table->dropColumn('url_video');
        });
    }
};
