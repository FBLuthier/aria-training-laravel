<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->logAction($user, 'created', 'Usuario creado', $user->toArray());
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // Solo registrar si hubo cambios
        if ($user->wasChanged()) {
            $original = $user->getOriginal();
            $changes = $user->getChanges();

            // Opcional: Filtrar campos sensibles como 'contrasena' o 'remember_token'
            unset($original['contrasena'], $original['remember_token']);
            unset($changes['contrasena'], $changes['remember_token']);

            $this->logAction($user, 'updated', 'Usuario actualizado', [
                'original' => $original,
                'changes' => $changes,
            ]);
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->logAction($user, 'deleted', 'Usuario eliminado (papelera)', $user->toArray());
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        $this->logAction($user, 'restored', 'Usuario restaurado', $user->toArray());
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        $this->logAction($user, 'force_deleted', 'Usuario eliminado permanentemente', $user->toArray());
    }

    /**
     * Helper para registrar en audit_logs
     */
    protected function logAction(User $model, string $action, string $description, array $details = []): void
    {
        if (! auth()->check()) {
            return; // No auditar acciones de sistema/consola por ahora, o usar un usuario sistema
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'details' => json_encode($details),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
