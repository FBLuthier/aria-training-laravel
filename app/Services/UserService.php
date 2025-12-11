<?php

namespace App\Services;

use App\Actions\Users\CreateUserAction;
use App\Actions\Users\UpdateUserAction;
use App\Data\UserData;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * Servicio centralizado para lógica de negocio de usuarios.
 * 
 * Encapsula operaciones complejas relacionadas con usuarios,
 * facilitando la reutilización y testeo del código.
 */
class UserService
{
    public function __construct(
        protected CreateUserAction $createUserAction,
        protected UpdateUserAction $updateUserAction
    ) {}

    /**
     * Crear un nuevo usuario a partir de datos validados.
     */
    public function create(UserData $data): User
    {
        return $this->createUserAction->execute($data);
    }

    /**
     * Actualizar un usuario existente.
     */
    public function update(User $user, UserData $data): User
    {
        return $this->updateUserAction->execute($user, $data);
    }

    /**
     * Eliminar un usuario (soft delete).
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Restaurar un usuario de la papelera.
     */
    public function restore(User $user): bool
    {
        return $user->restore();
    }

    /**
     * Eliminar un usuario permanentemente.
     */
    public function forceDelete(User $user): bool
    {
        return $user->forceDelete();
    }

    /**
     * Generar una contraseña segura aleatoria.
     */
    public function generateSecurePassword(int $length = 12): string
    {
        return Str::password($length);
    }

    /**
     * Restablecer la contraseña de un usuario.
     * 
     * @param User $user El usuario al que se le restablecerá la contraseña.
     * @param string|null $newPassword La nueva contraseña. Si es null, se genera una aleatoria.
     * @return string La nueva contraseña (sin hashear, para mostrar al admin).
     */
    public function resetPassword(User $user, ?string $newPassword = null): string
    {
        $password = $newPassword ?? $this->generateSecurePassword();
        
        $user->contrasena = $password; // El cast 'hashed' del modelo encripta automáticamente
        $user->save();

        return $password;
    }

    /**
     * Obtener usuarios visibles para un usuario específico.
     * 
     * @param User $viewer El usuario que está viendo la lista.
     * @param string|null $search Término de búsqueda opcional.
     * @param int|null $roleFilter Filtro por rol (UserRole value).
     * @param bool $includeTrashed Incluir usuarios eliminados.
     */
    public function getVisibleUsers(
        User $viewer,
        ?string $search = null,
        ?int $roleFilter = null,
        bool $includeTrashed = false
    ) {
        $query = User::query()
            ->withoutAdmins()
            ->visibleTo($viewer)
            ->byRole($roleFilter);

        if ($search) {
            $query->search($search);
        }

        if ($includeTrashed) {
            $query->onlyTrashed();
        }

        return $query;
    }
}
