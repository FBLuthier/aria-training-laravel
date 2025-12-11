<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserPolicy extends BaseAdminPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->esAdmin() || $user->esEntrenador();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Model $model): bool
    {
        if ($user->esAdmin()) {
            return true;
        }

        if ($user->esEntrenador()) {
            // Entrenador puede ver a sus atletas o a sí mismo
            return $model->entrenador_id === $user->id || $model->id === $user->id;
        }

        return $model->id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->esAdmin() || $user->esEntrenador();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Model $model): bool
    {
        if ($user->esAdmin()) {
            return true;
        }

        if ($user->esEntrenador()) {
            // Entrenador puede editar a sus atletas
            return $model->entrenador_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Model $model): bool
    {
        // Prevent deleting yourself
        if ($user->id === $model->id) {
            return false;
        }
        if ($this->isAdmin($user)) {
            return true;
        }
        if ($this->isEntrenador($user)) {
            // Entrenador solo puede eliminar a sus atletas
            return $model->entrenador_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Model $model): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Model $model): bool
    {
        return $this->isAdmin($user);
    }
}
