<?php

namespace App\Models\Builders;

/**
 * Trait que proporciona funcionalidad común para todos los Query Builders personalizados.
 *
 * Este trait elimina duplicación de código proporcionando métodos estándar
 * para búsqueda, filtrado de papelera, ordenamiento y operaciones comunes.
 *
 * MODO DE USO:
 * 1. Tu Query Builder debe extender de Illuminate\Database\Eloquent\Builder
 * 2. Usa este trait: use BaseQueryBuilder;
 * 3. Define la propiedad $searchableFields con los campos buscables
 * 4. Opcionalmente sobrescribe métodos si necesitas lógica especial
 *
 * EJEMPLO:
 * ```php
 * class EquipoQueryBuilder extends Builder {
 *     use BaseQueryBuilder;
 *
 *     protected array $searchableFields = ['nombre', 'descripcion'];
 * }
 * ```
 *
 * IMPORTANTE: La clase que usa este trait DEBE definir la propiedad $searchableFields.
 * El trait NO la define para evitar conflictos.
 */
trait BaseQueryBuilder
{
    // NOTA: Las clases que usan este trait DEBEN definir:
    // protected array $searchableFields = ['campo1', 'campo2'];

    // =======================================================================
    //  MÉTODOS DE BÚSQUEDA
    // =======================================================================

    /**
     * Busca en los campos especificados usando LIKE.
     *
     * @param  string|null  $search  Término de búsqueda
     * @param  array|null  $fields  Campos específicos (si es null usa $searchableFields)
     */
    public function search(?string $search, ?array $fields = null): self
    {
        if (empty($search)) {
            return $this;
        }

        // Usar campos proporcionados o los definidos en $searchableFields
        $searchFields = $fields ?? ($this->searchableFields ?? []);

        if (empty($searchFields)) {
            // Si no hay campos definidos, no hacer nada (evitar error)
            return $this;
        }

        return $this->where(function ($query) use ($search, $searchFields) {
            foreach ($searchFields as $field) {
                // Soporte para búsqueda en relaciones: "relacion.campo"
                if (str_contains($field, '.')) {
                    [$relation, $column] = explode('.', $field, 2);
                    $query->orWhereHas($relation, function ($q) use ($column, $search) {
                        $q->where($column, 'like', "%{$search}%");
                    });
                } else {
                    $query->orWhere($field, 'like', "%{$search}%");
                }
            }
        });
    }

    // =======================================================================
    //  MÉTODOS DE FILTRADO DE PAPELERA
    // =======================================================================

    /**
     * Aplica filtro de papelera basado en una condición.
     *
     * @param  bool  $showTrash  Si debe mostrar solo registros eliminados
     */
    public function trash(bool $showTrash = false): self
    {
        return $showTrash ? $this->onlyTrashed() : $this;
    }

    /**
     * Incluye solo registros activos (no eliminados).
     */
    public function active(): self
    {
        return $this->whereNull($this->getModel()->getDeletedAtColumn() ?? 'deleted_at');
    }

    // =======================================================================
    //  MÉTODOS DE ORDENAMIENTO
    // =======================================================================

    /**
     * Ordena por campo y dirección de forma segura.
     *
     * @param  string  $field  Campo por el que ordenar
     * @param  string  $direction  Dirección ('asc' o 'desc')
     */
    public function sortBy(string $field = 'id', string $direction = 'asc'): self
    {
        // Validar dirección
        $direction = strtolower($direction);
        if (! in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        return $this->orderBy($field, $direction);
    }

    /**
     * Ordena por múltiples campos.
     *
     * @param  array  $sorts  Array de ['campo' => 'direccion']
     */
    public function sortByMultiple(array $sorts): self
    {
        foreach ($sorts as $field => $direction) {
            $this->sortBy($field, $direction);
        }

        return $this;
    }

    // =======================================================================
    //  MÉTODOS DE FILTRADO COMBINADO
    // =======================================================================

    /**
     * Aplica filtros comunes de búsqueda y papelera.
     * Método de conveniencia que combina search() y trash().
     *
     * @param  string|null  $search  Término de búsqueda
     * @param  bool  $showTrash  Si debe mostrar papelera
     */
    public function applyFilters(?string $search = null, bool $showTrash = false): self
    {
        return $this->search($search)->trash($showTrash);
    }

    /**
     * Aplica filtros, ordenamiento y devuelve query lista para paginar.
     * Método todo-en-uno para casos típicos de listados.
     *
     * @param  string|null  $search  Término de búsqueda
     * @param  bool  $showTrash  Si debe mostrar papelera
     * @param  string  $sortField  Campo de ordenamiento
     * @param  string  $sortDirection  Dirección de ordenamiento
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

    // =======================================================================
    //  MÉTODOS DE UTILIDAD
    // =======================================================================

    /**
     * Obtiene los IDs de los registros actuales como array de strings.
     * Útil para selecciones masivas con Livewire.
     */
    public function getIds(): array
    {
        return $this->pluck($this->getModel()->getKeyName())
            ->map(fn ($id) => (string) $id)
            ->toArray();
    }

    /**
     * Cuenta registros que coinciden con los filtros actuales.
     */
    public function countFiltered(): int
    {
        return $this->count();
    }

    /**
     * Verifica si existen registros que coincidan con los filtros.
     */
    public function hasResults(): bool
    {
        return $this->exists();
    }

    // =======================================================================
    //  MÉTODOS DE SCOPES COMUNES
    // =======================================================================

    /**
     * Filtra por rango de fechas.
     *
     * @param  string  $field  Campo de fecha
     * @param  string|null  $from  Fecha desde (Y-m-d)
     * @param  string|null  $to  Fecha hasta (Y-m-d)
     */
    public function dateRange(string $field, ?string $from = null, ?string $to = null): self
    {
        if ($from) {
            $this->where($field, '>=', $from);
        }

        if ($to) {
            $this->where($field, '<=', $to);
        }

        return $this;
    }

    /**
     * Filtra registros creados en los últimos N días.
     *
     * @param  int  $days  Número de días
     * @param  string  $field  Campo de fecha (por defecto 'created_at')
     */
    public function recent(int $days = 7, string $field = 'created_at'): self
    {
        return $this->where($field, '>=', now()->subDays($days));
    }

    /**
     * Excluye IDs específicos de los resultados.
     * Útil para selección masiva con excepciones.
     *
     * @param  array  $ids  IDs a excluir
     */
    public function exceptIds(array $ids): self
    {
        if (empty($ids)) {
            return $this;
        }

        return $this->whereNotIn($this->getModel()->getKeyName(), $ids);
    }

    /**
     * Incluye solo los IDs especificados.
     *
     * @param  array  $ids  IDs a incluir
     */
    public function onlyIds(array $ids): self
    {
        if (empty($ids)) {
            return $this->whereRaw('1 = 0'); // Retorna query vacía
        }

        return $this->whereIn($this->getModel()->getKeyName(), $ids);
    }
}
