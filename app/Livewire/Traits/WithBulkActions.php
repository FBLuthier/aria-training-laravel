<?php

namespace App\Livewire\Traits;

/**
 * Trait para manejar selección múltiple y acciones en lote.
 * 
 * Este trait proporciona la funcionalidad estándar para:
 * - Seleccionar/deseleccionar items individuales
 * - Seleccionar/deseleccionar todos los items
 * - Mantener el estado de selección
 * 
 * REQUISITOS:
 * - El componente debe tener paginación (usa WithPagination)
 * - Debe tener una propiedad $search para filtrado
 * - Debe tener un modelo Eloquent para consultar
 */
trait WithBulkActions
{
    /** @var array IDs de los items seleccionados */
    public array $selectedItems = [];

    /** @var bool Estado del checkbox "Seleccionar Todo" */
    public bool $selectAll = false;

    /**
     * Hook que se ejecuta cuando cambia el valor de $selectAll.
     * Si es true, selecciona todos los items visibles en la página actual.
     * Si es false, deselecciona todos.
     */
    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $this->selectAllItems();
        } else {
            $this->selectedItems = [];
        }
    }

    /**
     * Selecciona todos los items que coinciden con los filtros actuales.
     * Este método debe ser sobrescrito por el componente que usa el trait.
     */
    protected function selectAllItems(): void
    {
        // Este método debe ser implementado en el componente
        // Ejemplo de implementación:
        // $this->selectedItems = Model::query()
        //     ->when($this->search, fn($q) => $q->where('column', 'like', '%' . $this->search . '%'))
        //     ->when($this->showingTrash, fn($q) => $q->onlyTrashed())
        //     ->pluck('id')
        //     ->map(fn($id) => (string) $id)
        //     ->toArray();
    }

    /**
     * Hook que se ejecuta cuando cambia la búsqueda.
     * Limpia las selecciones para evitar inconsistencias.
     */
    public function updatingSearch(): void
    {
        $this->clearSelections();
    }

    /**
     * Limpia todas las selecciones.
     */
    public function clearSelections(): void
    {
        $this->selectedItems = [];
        $this->selectAll = false;
    }

    /**
     * Retorna true si hay items seleccionados.
     */
    public function hasSelectedItems(): bool
    {
        return count($this->selectedItems) > 0;
    }

    /**
     * Retorna el número de items seleccionados.
     */
    public function selectedCount(): int
    {
        return count($this->selectedItems);
    }
}
