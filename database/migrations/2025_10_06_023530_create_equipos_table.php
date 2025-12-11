<?php

/**
 * =======================================================================
 * MIGRACIÓN: TABLA EQUIPOS
 * =======================================================================
 *
 * Crea la tabla de equipos de gimnasio.
 * Almacena el catálogo de equipos disponibles (mancuernas, barras, máquinas, etc.)
 * que pueden ser usados en los ejercicios.
 *
 * ESTRUCTURA:
 * - id: Identificador único del equipo
 * - nombre: Nombre del equipo (máx 45 caracteres, único)
 * - deleted_at: Soft delete para papelera (nullable)
 *
 * CARACTERÍSTICAS:
 * - Soft Deletes: Los registros eliminados van a papelera
 * - Sin timestamps: No requiere created_at/updated_at
 * - Único: No se permiten equipos duplicados
 *
 * RELACIONES:
 * - Tiene muchos: ejercicios (un equipo se usa en varios ejercicios)
 *
 * EJEMPLOS:
 * - "Mancuernas 10kg"
 * - "Barra Olímpica"
 * - "Máquina de Press"
 * - "Banco Plano"
 *
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
     * Crea la tabla equipos con soft deletes.
     */
    public function up(): void
    {
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 45)->unique();
            $table->softDeletes();

        });
    }

    /**
     * Revierte la migración.
     *
     * Elimina la tabla equipos si existe.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};
