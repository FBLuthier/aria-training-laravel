<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class RutinaPolicy extends BaseAdminPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user) || $this->isEntrenador($user);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Model $rutina): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isEntrenador($user)) {
            // El entrenador puede ver rutinas de SUS atletas
            return $rutina->usuario->entrenador_id === $user->id;
        }

        // El atleta puede ver SUS propias rutinas
        return $user->id === $rutina->usuario_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->isAdmin($user) || $this->isEntrenador($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Model $rutina): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isEntrenador($user)) {
            return $rutina->usuario->entrenador_id === $user->id;
        }

        return false; // Atletas no editan sus rutinas (por ahora)
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Model $rutina): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isEntrenador($user)) {
            return $rutina->usuario->entrenador_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Model $rutina): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Model $rutina): bool
    {
        return $this->isAdmin($user);
    }
}
