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
            if (Schema::hasColumn('rutinas', 'usuario_id') && ! Schema::hasColumn('rutinas', 'atleta_id')) {
                $table->renameColumn('usuario_id', 'atleta_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rutinas', function (Blueprint $table) {
            $table->renameColumn('atleta_id', 'usuario_id');
        });
    }
};
