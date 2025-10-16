<?php

namespace App\Actions;

use App\Livewire\Traits\WithAuditLogging;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

/**
 * Action para eliminar permanentemente un modelo.
 * Maneja la autorización, captura de valores para auditoría y dispatch del evento.
 */
class ForceDeleteModelAction
{
    use WithAuditLogging;
    /**
     * Ejecuta la eliminación permanente de un modelo.
     *
     * @param Model $model El modelo a eliminar permanentemente
     * @param bool $authorize Si debe verificar autorización (default: true)
     * @return array ['success' => bool, 'message' => string]
     */
    public function execute(Model $model, bool $authorize = true): array
    {
        // Verificar autorización si es necesario
        if ($authorize) {
            Gate::authorize('forceDelete', $model);
        }

        // Capturar valores ANTES de eliminar permanentemente para auditoría
        $oldValues = $model->toArray();

        // Ejecutar eliminación permanente
        $model->forceDelete();

        // Disparar evento de auditoría
        $this->auditForceDelete($model, $oldValues);

        return [
            'success' => true,
            'message' => class_basename($model) . ' eliminado permanentemente.',
        ];
    }

    /**
     * Ejecuta la eliminación permanente en lote.
     *
     * @param iterable $models Colección de modelos a eliminar permanentemente
     * @param bool $authorize Si debe verificar autorización para cada modelo
     * @return array ['success' => bool, 'message' => string, 'count' => int]
     */
    public function executeBulk(iterable $models, bool $authorize = true): array
    {
        $count = 0;
        $modelsData = [];

        // Capturar datos y verificar autorización
        foreach ($models as $model) {
            if ($authorize) {
                Gate::authorize('forceDelete', $model);
            }
            $modelsData[$model->id] = $model->toArray();
        }

        // Ejecutar eliminaciones permanentes
        foreach ($models as $model) {
            $model->forceDelete();
            
            // Auditoría individual
            $this->auditForceDelete($model, $modelsData[$model->id]);
            
            $count++;
        }

        $modelName = count($models) > 0 ? class_basename($models->first()) : 'Registros';

        return [
            'success' => true,
            'message' => "$count {$modelName}(s) eliminados permanentemente.",
            'count' => $count,
        ];
    }
}
