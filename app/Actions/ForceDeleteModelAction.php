<?php

namespace App\Actions;

use App\Livewire\Traits\WithAuditLogging;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

/**
 * =======================================================================
 * ACTION: ELIMINAR PERMANENTEMENTE (FORCE DELETE)
 * =======================================================================
 * 
 * ⚠️ PELIGRO: Esta Action elimina registros PERMANENTEMENTE de la base de datos.
 * NO es reversible. El registro se borra completamente y NO puede recuperarse.
 * 
 * RESPONSABILIDADES:
 * - Verificar autorización estricta del usuario
 * - Capturar datos completos para auditoría (única evidencia que quedará)
 * - Ejecutar eliminación física del registro
 * - Registrar acción en auditoría (crítico para trazabilidad)
 * - Retornar confirmación
 * 
 * DIFERENCIA VS SOFT DELETE:
 * - Soft Delete (DeleteModelAction): Marca deleted_at → REVERSIBLE
 * - Force Delete (esta Action): Borra físicamente → IRREVERSIBLE ⚠️
 * 
 * CUÁNDO USAR:
 * - Limpieza definitiva de registros antiguos
 * - Cumplimiento de regulaciones (GDPR - derecho al olvido)
 * - Vaciado de papelera
 * - Eliminación de datos sensibles
 * 
 * USO INDIVIDUAL:
 * ```php
 * // Solo desde papelera
 * $equipo = Equipo::onlyTrashed()->find($id);
 * $result = app(ForceDeleteModelAction::class)->execute($equipo);
 * // El equipo YA NO EXISTE en la BD
 * ```
 * 
 * USO EN LOTE:
 * ```php
 * $equiposEliminados = Equipo::onlyTrashed()->where('deleted_at', '<', now()->subMonths(6))->get();
 * $result = app(ForceDeleteModelAction::class)->executeBulk($equiposEliminados);
 * ```
 * 
 * SEGURIDAD:
 * - Requiere permiso 'forceDelete' (solo administradores)
 * - Captura TODOS los datos antes de eliminar
 * - Registra en audit_logs (evidencia permanente)
 * 
 * @package App\Actions
 * @since 1.0
 */
class ForceDeleteModelAction
{
    use WithAuditLogging;
    
    /**
     * Ejecuta la eliminación PERMANENTE de un modelo.
     * 
     * ⚠️ ADVERTENCIA: Esta operación NO puede deshacerse.
     * El registro se eliminará completamente de la base de datos.
     * 
     * Solo se debe usar cuando se está seguro de eliminar definitivamente.
     * 
     * @param Model $model El modelo a eliminar PERMANENTEMENTE
     * @param bool $authorize Si debe verificar permisos (default: true)
     * @return array ['success' => true, 'message' => 'Equipo eliminado permanentemente.']
     * @throws \Illuminate\Auth\Access\AuthorizationException Si no tiene permiso
     */
    public function execute(Model $model, bool $authorize = true): array
    {
        // Verificar permiso estricto (solo admins)
        if ($authorize) {
            Gate::authorize('forceDelete', $model);
        }

        // CRÍTICO: Capturar TODOS los datos antes de eliminar
        // Esta será la ÚNICA evidencia de que el registro existió
        $oldValues = $model->toArray();

        // Ejecutar eliminación PERMANENTE (borra físicamente)
        $model->forceDelete();

        // Registrar en auditoría (evidencia permanente)
        $this->auditForceDelete($model, $oldValues);

        return [
            'success' => true,
            'message' => class_basename($model) . ' eliminado permanentemente.',
        ];
    }

    /**
     * Ejecuta la eliminación PERMANENTE de múltiples modelos.
     * 
     * ⚠️ PELIGRO: Elimina todos los registros de la colección PERMANENTEMENTE.
     * 
     * @param iterable $models Colección de modelos a eliminar
     * @param bool $authorize Si debe verificar autorización por cada modelo
     * @return array ['success' => bool, 'message' => string, 'count' => int]
     */
    public function executeBulk(iterable $models, bool $authorize = true): array
    {
        $count = 0;
        $modelsData = [];

        // Verificar permisos y capturar datos
        foreach ($models as $model) {
            if ($authorize) {
                Gate::authorize('forceDelete', $model);
            }
            $modelsData[$model->id] = $model->toArray();
        }

        // Ejecutar eliminaciones permanentes
        foreach ($models as $model) {
            $model->forceDelete();
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
