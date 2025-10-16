<?php

namespace App\Livewire\Traits;

use App\Enums\SortDirection;

/**
 * Trait para manejar ordenamiento de tablas.
 * 
 * Este trait proporciona la funcionalidad estándar para:
 * - Ordenar por columnas
 * - Cambiar dirección de ordenamiento
 * - Mantener estado del ordenamiento
 * 
 * REQUISITOS:
 * - Debe tener propiedades: $sortField, $sortDirection
 */
trait HasSorting
{
    /** @var string Columna por la que se ordena la tabla */
    public string $sortField = 'id';

    /** @var SortDirection Dirección del ordenamiento */
    public SortDirection $sortDirection = SortDirection::ASC;

    /**
     * Cambia la columna y dirección del ordenamiento.
     */
    public function sortBy(string $field): void
    {
        $this->beforeSort();
        
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection->opposite();
        } else {
            $this->sortDirection = SortDirection::ASC;
        }
        
        $this->sortField = $field;
        
        $this->afterSort();
    }

    /**
     * Hook que se ejecuta antes de cambiar el ordenamiento.
     * Puede ser sobrescrito para limpiar estado.
     */
    protected function beforeSort(): void
    {
        // Override en el componente si necesitas esta funcionalidad
    }

    /**
     * Hook que se ejecuta después de cambiar el ordenamiento.
     * Puede ser sobrescrito para lógica adicional.
     */
    protected function afterSort(): void
    {
        // Override en el componente si necesitas esta funcionalidad
    }

    /**
     * Aplica el ordenamiento a una query.
     */
    protected function applySort($query)
    {
        return $query->orderBy($this->sortField, $this->sortDirection->value);
    }
}
