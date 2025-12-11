<?php

namespace App\Actions;

use App\Livewire\Traits\WithAuditLogging;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

/**
 * =======================================================================
 * ACTION: RESTAURAR MODELO (DESDE PAPELERA)
 * =======================================================================
 *
 * Esta Action maneja la restauración de modelos eliminados (soft deleted).
 * Permite recuperar registros que fueron enviados a la papelera.
 *
 * RESPONSABILIDADES:
 * - Verificar autorización del usuario
 * - Capturar estado actual (con deleted_at) para auditoría
 * - Ejecutar restore del modelo (quitar deleted_at)
 * - Registrar acción en el sistema de auditoría
 * - Retornar mensaje de confirmación
 *
 * RESTAURACIÓN:
 * Quita la marca deleted_at del registro, haciéndolo visible nuevamente.
 * El registro vuelve a aparecer en las consultas normales.
 *
 * USO INDIVIDUAL:
 * ```php
 * $result = app(RestoreModelAction::class)->execute($equipo);
 * // Resultado: ['success' => true, 'message' => 'Equipo restaurado exitosamente.']
 * ```
 *
 * USO EN LOTE:
 * ```php
 * $equipos = Equipo::onlyTrashed()->whereIn('id', [1, 2, 3])->get();
 * $result = app(RestoreModelAction::class)->executeBulk($equipos);
 * // Resultado: ['success' => true, 'message' => '3 Equipo(s) restaurados...', 'count' => 3]
 * ```
 *
 * @since 1.0
 */
class RestoreModelAction
{
    use WithAuditLogging;

    /**
     * Ejecuta la restauración de un modelo desde la papelera.
     *
     * Revierte el soft delete, haciendo el registro visible nuevamente.
     *
     * @param  Model  $model  El modelo a restaurar (debe estar eliminado)
     * @param  bool  $authorize  Si debe verificar permisos (default: true)
     * @return array ['success' => true, 'message' => 'Equipo restaurado exitosamente.']
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException Si no tiene permiso
     */
    public function execute(Model $model, bool $authorize = true): array
    {
        // Verificar que el usuario tiene permiso para restaurar
        if ($authorize) {
            Gate::authorize('restore', $model);
        }

        // Capturar estado actual (con deleted_at) para auditoría
        $modelValues = $model->toArray();

        // Ejecutar restauración (quita deleted_at)
        $model->restore();

        // Registrar acción en auditoría
        $this->auditRestore($model, $modelValues);

        return [
            'success' => true,
            'message' => class_basename($model).' restaurado exitosamente.',
        ];
    }

    /**
     * Ejecuta la restauración de múltiples modelos.
     *
     * @param  iterable  $models  Colección de modelos a restaurar
     * @param  bool  $authorize  Si debe verificar autorización por cada modelo
     * @return array ['success' => bool, 'message' => string, 'count' => int]
     */
    public function executeBulk(iterable $models, bool $authorize = true): array
    {
        $count = 0;
        $modelsData = [];

        // Verificar autorización y capturar datos
        foreach ($models as $model) {
            if ($authorize) {
                Gate::authorize('restore', $model);
            }
            $modelsData[$model->id] = $model->toArray();
        }

        // Ejecutar restauraciones
        foreach ($models as $model) {
            $model->restore();
            $this->auditRestore($model, $modelsData[$model->id]);
            $count++;
        }

        $modelName = count($models) > 0 ? class_basename($models->first()) : 'Registros';

        return [
            'success' => true,
            'message' => "$count {$modelName}(s) restaurados exitosamente.",
            'count' => $count,
        ];
    }
}
