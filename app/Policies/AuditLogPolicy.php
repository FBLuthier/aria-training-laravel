<?php

namespace App\Policies;

use App\Models\AuditLog;
use App\Models\User;

class AuditLogPolicy
{
    /**
     * Determine whether the user can view any audit logs.
     * Solo los administradores pueden ver los logs de auditoría.
     */
    public function viewAny(User $user): bool
    {
        return $user->tipo_usuario_id === 1; // Solo administradores
    }

    /**
     * Determine whether the user can view the audit log.
     * Solo los administradores pueden ver logs individuales de auditoría.
     */
    public function view(User $user, AuditLog $auditLog): bool
    {
        return $user->tipo_usuario_id === 1; // Solo administradores
    }

    /**
     * Determine whether the user can create audit logs.
     * Generalmente esto se maneja automáticamente por el sistema.
     */
    public function create(User $user): bool
    {
        return true; // Se crea automáticamente por el sistema
    }

    /**
     * Determine whether the user can update the audit log.
     * Los logs de auditoría no deben ser modificables por seguridad.
     */
    public function update(User $user, AuditLog $auditLog): bool
    {
        return false; // Los logs no deben ser editables
    }

    /**
     * Determine whether the user can delete the audit log.
     * Solo administradores pueden eliminar logs de auditoría.
     */
    public function delete(User $user, AuditLog $auditLog): bool
    {
        return $user->tipo_usuario_id === 1; // Solo administradores
    }

    /**
     * Determine whether the user can restore the audit log.
     * Los logs de auditoría generalmente no usan soft deletes.
     */
    public function restore(User $user, AuditLog $auditLog): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the audit log.
     * Solo administradores pueden eliminar permanentemente logs de auditoría.
     */
    public function forceDelete(User $user, AuditLog $auditLog): bool
    {
        return $user->tipo_usuario_id === 1; // Solo administradores
    }
}
