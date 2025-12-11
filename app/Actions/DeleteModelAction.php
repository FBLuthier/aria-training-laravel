<?php

namespace App\Actions;

use App\Livewire\Traits\WithAuditLogging;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

/**
 * =======================================================================
 * ACTION: ELIMINAR MODELO (SOFT DELETE)
 * =======================================================================
 *
 * Esta Action maneja la eliminación suave (soft delete) de modelos Eloquent.
 * Centraliza la lógica de eliminación para mantener consistencia en todo el sistema.
 *
 * RESPONSABILIDADES:
 * - Verificar autorización del usuario
 * - Capturar datos para auditoría ANTES de eliminar
 * - Ejecutar soft delete del modelo
 * - Registrar acción en el sistema de auditoría
 * - Retornar mensaje de confirmación
 *
 * SOFT DELETE:
 * No elimina el registro de la base de datos. Solo marca deleted_at.
 * El registro puede ser restaurado posteriormente.
 *
 * USO INDIVIDUAL:
 * ```php
 * $result = app(DeleteModelAction::class)->execute($equipo);
 * // Resultado: ['success' => true, 'message' => 'Equipo enviado a la papelera.']
 * ```
 *
 * USO EN LOTE:
 * ```php
 * $equipos = Equipo::whereIn('id', [1, 2, 3])->get();
 * $result = app(DeleteModelAction::class)->executeBulk($equipos);
 * // Resultado: ['success' => true, 'message' => '3 Equipo(s) enviados...', 'count' => 3]
 * ```
 *
 * FLUJO DE EJECUCIÓN:
 * 1. Verificar que el usuario tiene permiso 'delete'
 * 2. Capturar estado actual del modelo (para auditoría)
 * 3. Ejecutar $model->delete() (soft delete)
 * 4. Registrar en AuditLog
 * 5. Retornar confirmación
 *
 * AUDITORÍA:
 * Cada eliminación queda registrada en la tabla audit_logs con:
 * - Usuario que eliminó
 * - Modelo eliminado
 * - Valores antes de eliminar
 * - Timestamp de la acción
 *
 * @since 1.0
 */
class DeleteModelAction
{
    // =======================================================================
    //  TRAITS
    // =======================================================================

    use WithAuditLogging;

    // =======================================================================
    //  MÉTODOS PÚBLICOS
    // =======================================================================

    /**
     * Ejecuta la eliminación suave de un modelo individual.
     *
     * Este método es el punto de entrada principal para eliminar un registro.
     * Realiza todas las verificaciones necesarias y mantiene un registro
     * completo de la operación para auditoría.
     *
     * IMPORTANTE: Este NO elimina permanentemente el registro.
     * Solo marca deleted_at, permitiendo restauración posterior.
     *
     * Ejemplos:
     * ```php
     * // Desde un componente Livewire
     * $result = app(DeleteModelAction::class)->execute($equipo);
     *
     * // Sin autorización (ej: en seeders o jobs)
     * $result = app(DeleteModelAction::class)->execute($equipo, false);
     * ```
     *
     * @param  Model  $model  El modelo a eliminar (debe usar SoftDeletes)
     * @param  bool  $authorize  Si debe verificar permisos (default: true)
     * @return array ['success' => true, 'message' => 'Equipo enviado a la papelera.']
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException Si no tiene permiso
     */
    public function execute(Model $model, bool $authorize = true): array
    {
        // Paso 1: Verificar que el usuario tiene permiso para eliminar
        if ($authorize) {
            Gate::authorize('delete', $model);
        }

        // Paso 2: Capturar estado actual ANTES de eliminar
        // Esto es crucial para auditoría y posible restauración
        $oldValues = $model->toArray();

        // Paso 3: Ejecutar soft delete (marca deleted_at)
        $model->delete();

        // Paso 4: Registrar acción en sistema de auditoría
        $this->auditDelete($model, $oldValues);

        // Paso 5: Retornar confirmación con mensaje amigable
        return [
            'success' => true,
            'message' => class_basename($model).' enviado a la papelera.',
        ];
    }

    /**
     * Ejecuta la eliminación suave de múltiples modelos.
     *
     * Optimizado para operaciones en lote. Captura todos los datos
     * primero, luego ejecuta las eliminaciones.
     *
     * IMPORTANTE:
     * - Verifica autorización para CADA modelo si $authorize = true
     * - Registra auditoría individual para cada eliminación
     * - Retorna contador de registros eliminados
     *
     * Uso:
     * ```php
     * // Eliminar múltiples equipos seleccionados
     * $equipos = Equipo::whereIn('id', $selectedIds)->get();
     * $result = app(DeleteModelAction::class)->executeBulk($equipos);
     * // Resultado: ['success' => true, 'message' => '5 Equipo(s) enviados...', 'count' => 5]
     * ```
     *
     * @param  iterable  $models  Colección de modelos a eliminar
     * @param  bool  $authorize  Si debe verificar autorización por cada modelo
     * @return array ['success' => bool, 'message' => string, 'count' => int]
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException Si falla autorización
     */
    public function executeBulk(iterable $models, bool $authorize = true): array
    {
        $count = 0;
        $modelsData = [];

        // Fase 1: Capturar datos y verificar autorización
        // Se hace primero para fallar rápido si hay problema de permisos
        foreach ($models as $model) {
            if ($authorize) {
                Gate::authorize('delete', $model);
            }
            // Guardar datos actuales para auditoría
            $modelsData[$model->id] = $model->toArray();
        }

        // Fase 2: Ejecutar eliminaciones y auditoría
        foreach ($models as $model) {
            // Soft delete del modelo
            $model->delete();

            // Registrar auditoría con datos capturados
            $this->auditDelete($model, $modelsData[$model->id]);

            $count++;
        }

        // Obtener nombre del modelo para mensaje
        $modelName = count($models) > 0 ? class_basename($models->first()) : 'Registros';

        return [
            'success' => true,
            'message' => "$count {$modelName}(s) enviados a la papelera.",
            'count' => $count,
        ];
    }
}
