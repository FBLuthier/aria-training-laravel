<?php

namespace App\Livewire\Traits;

use Livewire\Attributes\Computed;

/**
 * Trait para manejar selección múltiple y acciones en lote.
 * 
 * Este trait proporciona la funcionalidad estándar para:
 * - Seleccionar/deseleccionar items individuales
 * - Seleccionar/deseleccionar todos los items de la página actual
 * - Seleccionar TODOS los items que coinciden con los filtros (optimizado para grandes volúmenes)
 * - Mantener el estado de selección
 * 
 * REQUISITOS:
 * - El componente debe tener paginación (usa WithPagination)
 * - Debe tener una propiedad $search para filtrado
 * - Debe tener un modelo Eloquent para consultar
 * - Debe implementar el método getFilteredQuery() que retorna la query con filtros aplicados
 */
trait WithBulkActions
{
    /** @var array IDs de los items seleccionados */
    public array $selectedItems = [];

    /** @var bool Estado del checkbox "Seleccionar Todo" */
    public bool $selectAll = false;

    /** @var bool Indica si se están seleccionando TODOS los registros (incluso los no visibles) */
    public bool $selectingAll = false;

    /** @var array IDs excluidos cuando se usa selectingAll */
    public array $exceptItems = [];

    /**
     * Hook que se ejecuta cuando cambia el valor de $selectAll.
     * Si es true, selecciona todos los items visibles en la página actual.
     * Si es false, deselecciona todos.
     */
    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $this->selectAllOnPage();
        } else {
            $this->clearSelections();
        }
    }

    /**
     * Selecciona todos los items de la PÁGINA ACTUAL solamente.
     */
    protected function selectAllOnPage(): void
    {
        $this->selectingAll = false;
        $this->exceptItems = [];
        $this->selectAllItems();
    }

    /**
     * Selecciona TODOS los items que coinciden con los filtros actuales.
     * Este método debe ser sobrescrito por el componente que usa el trait.
     * 
     * IMPORTANTE: Solo debe cargar los IDs de la página actual.
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
     * Activa el modo "Seleccionar Todos" para seleccionar TODOS los registros.
     * Esto es optimizado para grandes volúmenes de datos.
     */
    public function selectAllRecords(): void
    {
        $this->selectingAll = true;
        $this->selectedItems = [];
        $this->exceptItems = [];
        $this->selectAll = true;
    }

    /**
     * Desactiva el modo "Seleccionar Todos" y vuelve a la selección de página actual.
     */
    public function selectOnlyPage(): void
    {
        $this->selectingAll = false;
        $this->exceptItems = [];
        $this->selectAllOnPage();
    }

    /**
     * Excluye un item de la selección cuando se está en modo selectingAll.
     */
    public function toggleExcept(string $id): void
    {
        if (in_array($id, $this->exceptItems)) {
            $this->exceptItems = array_values(array_filter($this->exceptItems, fn($item) => $item !== $id));
        } else {
            $this->exceptItems[] = $id;
        }
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
        $this->selectingAll = false;
        $this->exceptItems = [];
    }

    /**
     * Retorna true si hay items seleccionados.
     */
    public function hasSelectedItems(): bool
    {
        return $this->selectingAll || count($this->selectedItems) > 0;
    }

    /**
     * Retorna el número de items seleccionados.
     * Si selectingAll está activo, retorna el total de registros filtrados.
     * 
     * @return int
     */
    #[Computed]
    public function selectedCount(): int
    {
        if ($this->selectingAll) {
            return $this->totalFilteredCount - count($this->exceptItems);
        }
        return count($this->selectedItems);
    }

    /**
     * Obtiene el total de registros que coinciden con los filtros actuales.
     * Este método debe ser sobrescrito por el componente que usa el trait.
     */
    protected function getTotalFilteredCount(): int
    {
        // Este método debe ser implementado en el componente
        // Ejemplo de implementación:
        // return Model::query()
        //     ->when($this->search, fn($q) => $q->where('column', 'like', '%' . $this->search . '%'))
        //     ->when($this->showingTrash, fn($q) => $q->onlyTrashed())
        //     ->count();
        return 0;
    }

    /**
     * Aplica la selección a una query.
     * Si selectingAll está activo, usa la query con filtros y excepciones.
     * Si no, usa whereIn con los IDs seleccionados.
     */
    protected function applySelectionToQuery($query)
    {
        if ($this->selectingAll) {
            // Aplicar filtros actuales y excluir los items marcados como excepciones
            $filteredQuery = $this->getFilteredQuery();
            $query->whereIn('id', function($subquery) use ($filteredQuery) {
                $subquery->select('id')->from($filteredQuery);
            });
            
            if (count($this->exceptItems) > 0) {
                $query->whereNotIn('id', $this->exceptItems);
            }
        } else {
            $query->whereIn('id', $this->selectedItems);
        }
        
        return $query;
    }

    /**
     * Obtiene la query base con todos los filtros aplicados.
     * Este método debe ser sobrescrito por el componente que usa el trait.
     */
    protected function getFilteredQuery()
    {
        // Este método debe ser implementado en el componente
        // Ejemplo de implementación:
        // return Model::query()
        //     ->when($this->search, fn($q) => $q->where('column', 'like', '%' . $this->search . '%'))
        //     ->when($this->showingTrash, fn($q) => $q->onlyTrashed());
    }
}
