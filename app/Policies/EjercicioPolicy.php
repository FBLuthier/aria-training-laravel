<?php

namespace App\Policies;

/**
 * =======================================================================
 * POLICY DE AUTORIZACIÓN PARA EJERCICIOS
 * =======================================================================
 * 
 * Esta Policy controla quién puede realizar operaciones sobre Ejercicios.
 * Extiende de BaseAdminPolicy, que implementa el patrón "solo administradores".
 * 
 * AUTORIZACIÓN:
 * - Hereda todos los permisos de administrador de BaseAdminPolicy.
 * - viewAny, view, create, update, delete, restore, forceDelete.
 * 
 * @package App\Policies
 */
class EjercicioPolicy extends BaseAdminPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user): bool
    {
        return $user->esAdmin() || $user->esEntrenador();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user, $model): bool
    {
        return $user->esAdmin() || $user->esEntrenador();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user): bool
    {
        return $user->esAdmin() || $user->esEntrenador();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, $model): bool
    {
        if ($user->esAdmin()) {
            return true;
        }

        if ($user->esEntrenador()) {
            // Solo puede editar si es el creador
            return $model->usuario_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($user, $model): bool
    {
        if ($user->esAdmin()) {
            return true;
        }

        if ($user->esEntrenador()) {
            // Solo puede eliminar si es el creador
            return $model->usuario_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore($user, $model): bool
    {
        if ($user->esAdmin()) {
            return true;
        }

        if ($user->esEntrenador()) {
            return $model->usuario_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete($user, $model): bool
    {
        if ($user->esAdmin()) {
            return true;
        }

        if ($user->esEntrenador()) {
            return $model->usuario_id === $user->id;
        }

        return false;
    }
}
