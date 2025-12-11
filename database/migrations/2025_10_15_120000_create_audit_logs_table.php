<?php

/**
 * =======================================================================
 * MIGRACIÓN: TABLA AUDIT LOGS (AUDITORÍA)
 * =======================================================================
 *
 * Crea la tabla de registros de auditoría del sistema.
 * Registra TODAS las acciones críticas realizadas en el sistema para
 * trazabilidad, seguridad y cumplimiento normativo.
 *
 * ESTRUCTURA:
 * - id: Identificador único del log
 * - user_id: FK nullable a usuarios (quién realizó la acción)
 * - action: Tipo de acción (create, update, delete, restore, force_delete)
 * - model_type: Clase del modelo afectado (App\Models\Equipo)
 * - model_id: ID del registro afectado
 * - old_values: JSON con valores antes del cambio (para update/delete)
 * - new_values: JSON con valores después del cambio (para create/update)
 * - ip_address: IP desde donde se hizo la acción (soporte IPv6)
 * - user_agent: Navegador/cliente usado
 * - timestamps: created_at, updated_at
 *
 * ÍNDICES:
 * Para optimización de consultas frecuentes:
 * - model_type + model_id: Buscar todos los cambios de un registro
 * - user_id: Buscar todas las acciones de un usuario
 * - action: Filtrar por tipo de acción
 * - created_at: Ordenar cronológicamente
 *
 * USOS:
 * - Seguridad: Detectar acciones sospechosas
 * - Compliance: Cumplir con regulaciones (SOX, GDPR)
 * - Debugging: Rastrear cambios inesperados
 * - Recuperación: Restaurar datos borrados
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
     * Crea la tabla audit_logs con todos sus campos, constraints e índices.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->string('action'); // create, update, delete, restore, force_delete
            $table->string('model_type'); // App\Models\Equipo
            $table->unsignedBigInteger('model_id');
            $table->json('old_values')->nullable(); // Valores anteriores para updates
            $table->json('new_values')->nullable(); // Valores nuevos para creates/updates
            $table->string('ip_address', 45)->nullable(); // Soporte para IPv6
            $table->text('user_agent')->nullable();
            $table->timestamps();

            // Índices para mejorar el rendimiento de consultas
            $table->index(['model_type', 'model_id']);
            $table->index(['user_id']);
            $table->index(['action']);
            $table->index(['created_at']);
        });
    }

    /**
     * Revierte la migración.
     *
     * Elimina la tabla audit_logs si existe.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
