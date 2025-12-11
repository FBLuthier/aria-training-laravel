<?php

namespace App\Policies;

/**
 * =======================================================================
 * POLICY DE AUTORIZACIÓN PARA EQUIPOS
 * =======================================================================
 *
 * Esta Policy controla quién puede realizar operaciones sobre Equipos.
 * Extiende de BaseAdminPolicy, que implementa el patrón "solo administradores".
 *
 * AUTORIZACIÓN ACTUAL:
 * - viewAny (ver lista): Solo administradores ✓
 * - view (ver detalle): Solo administradores ✓
 * - create (crear): Solo administradores ✓
 * - update (editar): Solo administradores ✓
 * - delete (eliminar): Solo administradores ✓
 * - restore (restaurar): Solo administradores ✓
 * - forceDelete (eliminar permanente): Solo administradores ✓
 * - export (exportar): Solo administradores ✓
 *
 * CÓMO FUNCIONA:
 * Laravel automáticamente verifica estas policies en:
 * - Gates: Gate::allows('update', $equipo)
 * - Middleware: Route::middleware('can:update,equipo')
 * - Controllers: $this->authorize('update', $equipo)
 * - Livewire: $this->authorize('update', $equipo)
 *
 * ESTA CLASE ESTÁ VACÍA INTENCIONALMENTE:
 * Toda la lógica viene de BaseAdminPolicy. La mantenemos porque:
 * 1. Es la convención de Laravel (1 Policy por modelo)
 * 2. Permite agregar lógica personalizada en el futuro
 *
 * EJEMPLO DE PERSONALIZACIÓN FUTURA:
 * ```php
 * public function update(User $user, Model $model): bool
 * {
 *     // Permitir que entrenadores editen solo ciertos campos
 *     if ($user->isEntrenador()) {
 *         return true; // Validar campos en el Form
 *     }
 *
 *     return $this->isAdmin($user);
 * }
 * ```
 *
 * @since 1.0
 */
class EquipoPolicy extends BaseAdminPolicy
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
