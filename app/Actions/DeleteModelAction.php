<?php

namespace App\Actions;

use App\Livewire\Traits\WithAuditLogging;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

/**
 * Action para eliminar (soft delete) un modelo.
 * Maneja la autorización, captura de valores para auditoría y dispatch del evento.
 */
class DeleteModelAction
{
    use WithAuditLogging;
    /**
     * Ejecuta la eliminación suave de un modelo.
     *
     * @param Model $model El modelo a eliminar
     * @param bool $authorize Si debe verificar autorización (default: true)
     * @return array ['success' => bool, 'message' => string]
     */
    public function execute(Model $model, bool $authorize = true): array
    {
        // Verificar autorización si es necesario
        if ($authorize) {
            Gate::authorize('delete', $model);
        }

        // Capturar valores ANTES de eliminar para auditoría
        $oldValues = $model->toArray();

        // Ejecutar eliminación suave
        $model->delete();

        // Disparar evento de auditoría
        $this->auditDelete($model, $oldValues);

        return [
            'success' => true,
            'message' => class_basename($model) . ' enviado a la papelera.',
        ];
    }

    /**
     * Ejecuta la eliminación en lote.
     *
     * @param iterable $models Colección de modelos a eliminar
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
                Gate::authorize('delete', $model);
            }
            $modelsData[$model->id] = $model->toArray();
        }

        // Ejecutar eliminaciones
        foreach ($models as $model) {
            $model->delete();
            
            // Auditoría individual
            $this->auditDelete($model, $modelsData[$model->id]);
            
            $count++;
        }

        $modelName = count($models) > 0 ? class_basename($models->first()) : 'Registros';

        return [
            'success' => true,
            'message' => "$count {$modelName}(s) enviados a la papelera.",
            'count' => $count,
        ];
    }
}
