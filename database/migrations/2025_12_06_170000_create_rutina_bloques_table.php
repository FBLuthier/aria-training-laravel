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
        // 1. Crear tabla rutina_bloques
        if (!Schema::hasTable('rutina_bloques')) {
            Schema::create('rutina_bloques', function (Blueprint $table) {
                $table->id();
                $table->foreignId('rutina_dia_id')->constrained('rutina_dias')->onDelete('cascade');
                $table->string('nombre');
                $table->integer('orden')->default(0);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 2. Añadir FK a rutina_ejercicios
        Schema::table('rutina_ejercicios', function (Blueprint $table) {
            if (!Schema::hasColumn('rutina_ejercicios', 'rutina_bloque_id')) {
                $table->foreignId('rutina_bloque_id')
                      ->nullable()
                      ->after('rutina_dia_id')
                      ->constrained('rutina_bloques')
                      ->onDelete('set null'); // Si se borra el bloque, los ejercicios quedan "huerfanos" de bloque pero no se borran del día (seguridad)
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rutina_ejercicios', function (Blueprint $table) {
            if (Schema::hasColumn('rutina_ejercicios', 'rutina_bloque_id')) {
                $table->dropForeign(['rutina_bloque_id']);
                $table->dropColumn('rutina_bloque_id');
            }
        });

        Schema::dropIfExists('rutina_bloques');
    }
};
