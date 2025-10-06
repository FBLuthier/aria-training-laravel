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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('usuario', 15)->unique();
            $table->string('correo', 45)->unique();
            $table->string('contrasena', 255);
            $table->string('nombre_1', 15);
            $table->string('nombre_2', 15)->nullable();
            $table->string('apellido_1', 15);
            $table->string('apellido_2', 15)->nullable();
            $table->string('telefono', 15);
            $table->date('fecha_nacimiento');
            $table->boolean('estado');
            $table->date('fecha_eliminacion')->nullable();
            $table->unsignedBigInteger('id_tipo_usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};