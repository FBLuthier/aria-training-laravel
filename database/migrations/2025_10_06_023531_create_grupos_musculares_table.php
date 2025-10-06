<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupos_musculares', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 45);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupos_musculares');
    }
};