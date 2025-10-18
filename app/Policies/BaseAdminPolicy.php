<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Clase base abstracta para todas las Policies de administrador.
 * 
 * Proporciona implementación estándar de autorización donde solo
 * los administradores pueden realizar todas las operaciones CRUD.
 * 
 * MODO DE USO:
 * 1. Crea tu Policy extendiendo de esta clase
 * 2. Si TODA la lógica es "solo admin", no necesitas sobrescribir nada
 * 3. Si necesitas lógica especial, sobrescribe el método específico
 * 
 * EJEMPLO BÁSICO (Policy vacía - hereda todo):
 * ```php
 * class EquipoPolicy extends BaseAdminPolicy
 * {
 *     // ¡Vacía! Hereda toda la lógica de BaseAdminPolicy
 * }
 * ```
 * 
 * EJEMPLO AVANZADO (lógica personalizada):
 * ```php
 * class RutinaPolicy extends BaseAdminPolicy
 * {
 *     // Sobrescribir para lógica especial
 *     public function update(User $user, Model $model): bool
 *     {
 *         // Admins pueden editar todas, entrenadores solo las suyas
 *         return $this->isAdmin($user) || $user->id === $model->entrenador_id;
 *     }
 * }
 * ```
 */
abstract class BaseAdminPolicy
{
    /**
     * ID del tipo de usuario Administrador.
     * Ajusta este valor si tu sistema usa un ID diferente.
     */
    protected const ADMIN_TYPE_ID = 1;

    // =======================================================================
    //  MÉTODO HELPER PRINCIPAL
    // =======================================================================

    /**
     * Verifica si el usuario es administrador.
     * 
     * @param User $user
     * @return bool
     */
    protected function isAdmin(User $user): bool
    {
        return $user->tipo_usuario_id === self::ADMIN_TYPE_ID;
    }

    /**
     * Verifica si el usuario es el dueño del modelo.
     * Útil para sobrescribir métodos con lógica de "propio recurso".
     * 
     * @param User $user
     * @param Model $model
     * @param string $foreignKey Campo que relaciona el modelo con el usuario
     * @return bool
     */
    protected function isOwner(User $user, Model $model, string $foreignKey = 'user_id'): bool
    {
        return $user->id === $model->{$foreignKey};
    }

    /**
     * Verifica si el usuario es admin O dueño del recurso.
     * 
     * @param User $user
     * @param Model $model
     * @param string $foreignKey
     * @return bool
     */
    protected function isAdminOrOwner(User $user, Model $model, string $foreignKey = 'user_id'): bool
    {
        return $this->isAdmin($user) || $this->isOwner($user, $model, $foreignKey);
    }

    // =======================================================================
    //  MÉTODOS CRUD ESTÁNDAR (SOLO ADMIN POR DEFECTO)
    // =======================================================================

    /**
     * Determina si el usuario puede ver cualquier modelo.
     * 
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determina si el usuario puede ver el modelo específico.
     * 
     * @param User $user
     * @param Model $model
     * @return bool
     */
    public function view(User $user, Model $model): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determina si el usuario puede crear modelos.
     * 
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determina si el usuario puede actualizar el modelo.
     * 
     * @param User $user
     * @param Model $model
     * @return bool
     */
    public function update(User $user, Model $model): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determina si el usuario puede eliminar el modelo (soft delete).
     * 
     * @param User $user
     * @param Model $model
     * @return bool
     */
    public function delete(User $user, Model $model): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determina si el usuario puede restaurar el modelo eliminado.
     * 
     * @param User $user
     * @param Model $model
     * @return bool
     */
    public function restore(User $user, Model $model): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determina si el usuario puede eliminar permanentemente el modelo.
     * 
     * @param User $user
     * @param Model $model
     * @return bool
     */
    public function forceDelete(User $user, Model $model): bool
    {
        return $this->isAdmin($user);
    }

    //  MÉTODOS ADICIONALES ÚTILES
    // =======================================================================

    /**
     * Determina si el usuario puede realizar acciones masivas.
     */
    public function bulkActions(User $user): bool
    {
        return $this->isAdmin($user);
    }
    
    /**
     * Determina si el usuario puede exportar datos.
     * 
     * @param User $user
     * @param string|null $modelClass Clase del modelo a exportar
     * @return bool
     */
    public function export(User $user, ?string $modelClass = null): bool
    {
        return $this->isAdmin($user);
    }
    
    /**
     * Determina si el usuario puede importar datos.
     * 
     * @param User $user
     * @return bool
     */
    public function import(User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determina si el usuario puede ver el historial de auditoría.
     * 
     * @param User $user
     * @param Model|null $model
     * @return bool
     */
    public function viewAudit(User $user, ?Model $model = null): bool
    {
        return $this->isAdmin($user);
    }

    // =======================================================================
    //  MÉTODOS OPCIONALES PARA LÓGICA MULTI-ROL
    // =======================================================================

    /**
     * Verifica si el usuario tiene un rol específico.
     * Útil si tu sistema tiene múltiples roles (admin, entrenador, atleta).
     * 
     * @param User $user
     * @param int|array $typeIds ID(s) de tipo de usuario
     * @return bool
     */
    protected function hasRole(User $user, int|array $typeIds): bool
    {
        if (is_array($typeIds)) {
            return in_array($user->tipo_usuario_id, $typeIds);
        }

        return $user->tipo_usuario_id === $typeIds;
    }

    /**
     * Verifica si el usuario tiene cualquiera de los roles especificados.
     * 
     * @param User $user
     * @param array $typeIds
     * @return bool
     */
    protected function hasAnyRole(User $user, array $typeIds): bool
    {
        return in_array($user->tipo_usuario_id, $typeIds);
    }

    /**
     * Verifica si el usuario tiene todos los roles especificados.
     * (Útil en sistemas con roles múltiples simultáneos)
     * 
     * @param User $user
     * @param array $typeIds
     * @return bool
     */
    protected function hasAllRoles(User $user, array $typeIds): bool
    {
        // En el sistema actual cada usuario tiene un solo rol
        // pero este método está aquí para extensibilidad futura
        return in_array($user->tipo_usuario_id, $typeIds);
    }
}
