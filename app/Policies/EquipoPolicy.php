<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Equipo;
use App\Models\User;

class EquipoPolicy
{
    /**
     * Determine whether the user can view any models.
     * Solo los administradores pueden ver la lista de equipos.
     */
    public function viewAny(User $user): bool
    {
        return $user->tipo_usuario_id === 1; // Solo administradores
    }

    /**
     * Determine whether the user can view the model.
     * Solo los administradores pueden ver equipos individuales.
     */
    public function view(User $user, Equipo $equipo): bool
    {
        return $user->tipo_usuario_id === 1; // Solo administradores
    }

    /**
     * Determine whether the user can create models.
     * Solo los administradores pueden crear equipos.
     */
    public function create(User $user): bool
    {
        return $user->tipo_usuario_id === 1; // Solo administradores
    }

    /**
     * Determine whether the user can update the model.
     * Solo los administradores pueden actualizar equipos.
     */
    public function update(User $user, Equipo $equipo): bool
    {
        return $user->tipo_usuario_id === 1; // Solo administradores
    }

    /**
     * Determine whether the user can delete the model.
     * Solo los administradores pueden eliminar equipos (soft delete).
     */
    public function delete(User $user, Equipo $equipo): bool
    {
        return $user->tipo_usuario_id === 1; // Solo administradores
    }

    /**
     * Determine whether the user can restore the model.
     * Solo los administradores pueden restaurar equipos desde la papelera.
     */
    public function restore(User $user, Equipo $equipo): bool
    {
        return $user->tipo_usuario_id === 1; // Solo administradores
    }

    /**
     * Determine whether the user can permanently delete the model.
     * Solo los administradores pueden eliminar permanentemente equipos.
     */
    public function forceDelete(User $user, Equipo $equipo): bool
    {
        return $user->tipo_usuario_id === 1; // Solo administradores
    }
}
