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
            $table->string('nombre', 255)->change();
        });
    }

    public function down(): void
    {
        Schema::table('ejercicios', function (Blueprint $table) {
            $table->string('nombre', 45)->change();
        });
    }
};
