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
        Schema::table('usuarios', function (Blueprint $table) {
            // Primero eliminamos la clave foránea existente
            $table->dropForeign(['id_tipo_usuario']);

            // Renombramos la columna
            $table->renameColumn('id_tipo_usuario', 'tipo_usuario_id');

            // Recreamos la clave foránea con el nuevo nombre
            $table->foreign('tipo_usuario_id')->references('id')->on('tipo_usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Primero eliminamos la clave foránea existente
            $table->dropForeign(['tipo_usuario_id']);

            // Renombramos la columna de vuelta
            $table->renameColumn('tipo_usuario_id', 'id_tipo_usuario');

            // Recreamos la clave foránea con el nombre original
            $table->foreign('id_tipo_usuario')->references('id')->on('tipo_usuarios');
        });
    }
};
