<?php

namespace App\Models\Builders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserQueryBuilder extends Builder
{
    use BaseQueryBuilder;

    protected array $searchableFields = [
        'nombre_1',
        'apellido_1',
        'correo',
        'usuario',
    ];

    /**
     * Filtra usuarios visibles para el usuario actual.
     *
     * - Admin: Ve todo (excepto otros admins en la lista general si se requiere,
     *   pero aquí controlamos visibilidad general).
     * - Entrenador: Solo ve a sus atletas asignados.
     */
    public function visibleTo(User $user): self
    {
        if ($user->esEntrenador()) {
            return $this->where('entrenador_id', $user->id)
                ->where('tipo_usuario_id', UserRole::Atleta);
        }

        // Admin ve todo, pero por defecto en la tabla solemos ocultar a otros admins
        // para evitar ediciones accidentales, aunque eso suele ser un filtro de UI.
        // Aquí dejaremos que vea todo, y el filtro específico se aplique después.
        return $this;
    }

    /**
     * Filtra por rol específico.
     */
    public function byRole($role): self
    {
        if (empty($role)) {
            return $this;
        }

        // Si el rol es Admin, y queremos protegerlo o filtrarlo explícitamente
        if ($role === UserRole::Admin->value || $role === UserRole::Admin) {
            return $this->where('tipo_usuario_id', UserRole::Admin);
        }

        return $this->where('tipo_usuario_id', $role);
    }

    /**
     * Excluye a los administradores de la consulta.
     */
    public function withoutAdmins(): self
    {
        return $this->where('tipo_usuario_id', '!=', UserRole::Admin);
    }
}
