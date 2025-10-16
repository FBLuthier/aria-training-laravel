<?php

namespace App\Actions;

use App\Livewire\Traits\WithAuditLogging;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

/**
 * Action para restaurar un modelo desde la papelera.
 * Maneja la autorización, captura de valores para auditoría y dispatch del evento.
 */
class RestoreModelAction
{
    use WithAuditLogging;
    /**
     * Ejecuta la restauración de un modelo.
     *
     * @param Model $model El modelo a restaurar
     * @param bool $authorize Si debe verificar autorización (default: true)
     * @return array ['success' => bool, 'message' => string]
     */
    public function execute(Model $model, bool $authorize = true): array
    {
        // Verificar autorización si es necesario
        if ($authorize) {
            Gate::authorize('restore', $model);
        }

        // Capturar valores ANTES de restaurar para auditoría
        $modelValues = $model->toArray();

        // Ejecutar restauración
        $model->restore();

        // Disparar evento de auditoría
        $this->auditRestore($model, $modelValues);

        return [
            'success' => true,
            'message' => class_basename($model) . ' restaurado exitosamente.',
        ];
    }

    /**
     * Ejecuta la restauración en lote.
     *
     * @param iterable $models Colección de modelos a restaurar
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
                Gate::authorize('restore', $model);
            }
            $modelsData[$model->id] = $model->toArray();
        }

        // Ejecutar restauraciones
        foreach ($models as $model) {
            $model->restore();
            
            // Auditoría individual
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
