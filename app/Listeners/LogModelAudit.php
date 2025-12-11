<?php

namespace App\Listeners;

use App\Events\ModelAudited;
use App\Models\AuditLog;

class LogModelAudit
{
    /**
     * Handle the event.
     * Crea un registro de auditoría cuando se dispara el evento ModelAudited.
     */
    public function handle(ModelAudited $event): void
    {
        // Crear una clave única para evitar duplicados
        $uniqueKey = md5(
            $event->action.
            get_class($event->model).
            $event->model->getKey().
            now()->format('Y-m-d H:i:s')
        );

        // Verificar si ya existe un registro reciente (últimos 5 segundos) para evitar duplicados
        $existingLog = AuditLog::where('model_type', get_class($event->model))
            ->where('model_id', $event->model->getKey())
            ->where('action', $event->action)
            ->where('created_at', '>=', now()->subSeconds(5))
            ->first();

        if ($existingLog) {
            return; // Ya existe un registro reciente, evitar duplicado
        }

        // Obtener información adicional del contexto HTTP
        $ipAddress = request()->ip();
        $userAgent = request()->userAgent();

        // Crear el registro de auditoría
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $event->action,
            'model_type' => get_class($event->model),
            'model_id' => $event->model->getKey(),
            'old_values' => $event->oldValues,
            'new_values' => $event->newValues,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }
}
