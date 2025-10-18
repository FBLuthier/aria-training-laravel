<?php

/**
 * =======================================================================
 * MIGRACIÓN: TABLA USUARIOS
 * =======================================================================
 * 
 * Crea la tabla principal de usuarios del sistema.
 * Almacena información de todos los tipos de usuarios (Admin, Entrenador, Atleta).
 * 
 * ESTRUCTURA:
 * - id: Identificador único del usuario
 * - usuario: Nombre de usuario (3-15 caracteres, único)
 * - correo: Email único del usuario
 * - contrasena: Hash bcrypt de la contraseña
 * - nombre_1, nombre_2: Primer y segundo nombre
 * - apellido_1, apellido_2: Primer y segundo apellido
 * - telefono: Teléfono de contacto
 * - fecha_nacimiento: Fecha de nacimiento del usuario
 * - estado: Estado activo/inactivo (boolean)
 * - fecha_eliminacion: Fecha de eliminación lógica
 * - id_tipo_usuario: FK a tipo_usuarios (rol del usuario)
 * - timestamps: created_at, updated_at
 * 
 * ÍNDICES:
 * - usuario: Único, para login
 * - correo: Único, para recuperación de contraseña
 * 
 * RELACIONES:
 * - Pertenece a: tipo_usuarios (FK: id_tipo_usuario)
 * - Tiene muchas: rutinas, audit_logs
 * 
 * @package Database\Migrations
 * @since 1.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     * 
     * Crea la tabla usuarios con todos sus campos y constraints.
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
     * Revierte la migración.
     * 
     * Elimina la tabla usuarios si existe.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};