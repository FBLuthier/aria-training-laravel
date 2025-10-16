<?php

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

/**
 * Query Builder personalizado para el modelo Equipo.
 * 
 * Proporciona métodos fluidos para filtrar, ordenar y manipular
 * consultas de equipos de forma reutilizable y testeable.
 */
class EquipoQueryBuilder extends Builder
{
    /**
     * Filtra equipos por término de búsqueda.
     * Busca en el campo 'nombre'.
     *
     * @param string|null $search Término de búsqueda
     * @return self
     */
    public function search(?string $search): self
    {
        if (empty($search)) {
            return $this;
        }

        return $this->where('nombre', 'like', "%{$search}%");
    }

    /**
     * Filtra solo equipos eliminados (en papelera).
     *
     * @return self
     */
    public function onlyTrashed(): self
    {
        return parent::onlyTrashed();
    }

    /**
     * Incluye equipos eliminados en los resultados.
     *
     * @return self
     */
    public function withTrashed(): self
    {
        return parent::withTrashed();
    }

    /**
     * Aplica filtro de papelera basado en una condición.
     *
     * @param bool $showTrash Si debe mostrar papelera
     * @return self
     */
    public function trash(bool $showTrash = false): self
    {
        return $showTrash ? $this->onlyTrashed() : $this;
    }

    /**
     * Ordena por campo y dirección.
     *
     * @param string $field Campo por el que ordenar
     * @param string $direction Dirección ('asc' o 'desc')
     * @return self
     */
    public function sortBy(string $field = 'id', string $direction = 'asc'): self
    {
        return $this->orderBy($field, $direction);
    }

    /**
     * Aplica filtros comunes de búsqueda y papelera.
     * Este es un método de conveniencia que combina search() y trash().
     *
     * @param string|null $search Término de búsqueda
     * @param bool $showTrash Si debe mostrar papelera
     * @return self
     */
    public function applyFilters(?string $search = null, bool $showTrash = false): self
    {
        return $this->search($search)->trash($showTrash);
    }

    /**
     * Aplica filtros y ordenamiento comunes.
     * Método todo-en-uno para casos típicos.
     *
     * @param string|null $search Término de búsqueda
     * @param bool $showTrash Si debe mostrar papelera
     * @param string $sortField Campo de ordenamiento
     * @param string $sortDirection Dirección de ordenamiento
     * @return self
     */
    public function filtered(
        ?string $search = null,
        bool $showTrash = false,
        string $sortField = 'id',
        string $sortDirection = 'asc'
    ): self {
        return $this
            ->search($search)
            ->trash($showTrash)
            ->sortBy($sortField, $sortDirection);
    }

    /**
     * Scope para obtener equipos activos (no eliminados).
     *
     * @return self
     */
    public function active(): self
    {
        return $this->whereNull('deleted_at');
    }

    /**
     * Obtiene IDs de los registros actuales.
     * Útil para selecciones masivas.
     *
     * @return array
     */
    public function getIds(): array
    {
        return $this->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();
    }
}
