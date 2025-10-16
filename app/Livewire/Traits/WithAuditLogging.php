<?php

namespace App\Livewire\Traits;

use App\Events\ModelAudited;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait para manejar auditoría de operaciones en modelos.
 * 
 * Este trait proporciona métodos helper para disparar eventos de auditoría
 * de forma consistente en toda la aplicación.
 * 
 * Simplifica la auditoría de operaciones CRUD:
 * - create
 * - update
 * - delete
 * - restore
 * - force_delete
 */
trait WithAuditLogging
{
    /**
     * Audita una operación de creación.
     *
     * @param Model $model El modelo creado
     * @return void
     */
    protected function auditCreate(Model $model): void
    {
        ModelAudited::dispatch('create', $model, null, $model->toArray());
    }

    /**
     * Audita una operación de actualización.
     *
     * @param Model $model El modelo actualizado
     * @param array $oldValues Los valores anteriores del modelo
     * @return void
     */
    protected function auditUpdate(Model $model, array $oldValues): void
    {
        ModelAudited::dispatch('update', $model, $oldValues, $model->toArray());
    }

    /**
     * Audita una operación de eliminación (soft delete).
     *
     * @param Model $model El modelo eliminado
     * @param array $modelValues Los valores del modelo antes de eliminar
     * @return void
     */
    protected function auditDelete(Model $model, array $modelValues): void
    {
        ModelAudited::dispatch('delete', $model, $modelValues, null);
    }

    /**
     * Audita una operación de restauración.
     *
     * @param Model $model El modelo restaurado
     * @param array $modelValues Los valores del modelo al momento de restaurar
     * @return void
     */
    protected function auditRestore(Model $model, array $modelValues): void
    {
        ModelAudited::dispatch('restore', $model, null, $modelValues);
    }

    /**
     * Audita una operación de eliminación permanente.
     *
     * @param Model $model El modelo eliminado permanentemente
     * @param array $modelValues Los valores del modelo antes de eliminar
     * @return void
     */
    protected function auditForceDelete(Model $model, array $modelValues): void
    {
        ModelAudited::dispatch('force_delete', $model, $modelValues, null);
    }

    /**
     * Audita automáticamente una operación create o update basándose en el estado del modelo.
     * 
     * @param Model $model El modelo a auditar
     * @param array|null $oldValues Los valores anteriores (para updates)
     * @return void
     */
    protected function auditSave(Model $model, ?array $oldValues = null): void
    {
        if ($model->wasRecentlyCreated) {
            $this->auditCreate($model);
        } else {
            $this->auditUpdate($model, $oldValues ?? []);
        }
    }

    /**
     * Ejecuta una operación y la audita automáticamente.
     * 
     * Este es un método helper que:
     * 1. Captura valores anteriores si es necesario
     * 2. Ejecuta la operación
     * 3. Dispara el evento de auditoría
     * 
     * @param Model $model El modelo a operar
     * @param string $action La acción (create, update, delete, restore, force_delete)
     * @param callable $operation La operación a ejecutar sobre el modelo
     * @return mixed El resultado de la operación
     */
    protected function performAndAudit(Model $model, string $action, callable $operation)
    {
        // Capturar valores antes de la operación (excepto para create)
        $oldValues = $action !== 'create' ? $model->toArray() : null;
        
        // Ejecutar la operación
        $result = $operation($model);
        
        // Determinar valores nuevos según la acción
        $newValues = match($action) {
            'create', 'restore', 'update' => $model->fresh()?->toArray() ?? $model->toArray(),
            default => null,
        };
        
        // Disparar evento de auditoría
        ModelAudited::dispatch($action, $model, $oldValues, $newValues);
        
        return $result;
    }
}
