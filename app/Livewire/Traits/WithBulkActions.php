<?php

namespace App\Livewire\Traits;

use Livewire\Attributes\Computed;

/**
 * =======================================================================
 * TRAIT PARA ACCIONES EN LOTE (BULK ACTIONS)
 * =======================================================================
 * 
 * Este trait implementa toda la lógica necesaria para seleccionar múltiples
 * registros y realizar acciones masivas sobre ellos. Está optimizado para
 * manejar grandes volúmenes de datos eficientemente.
 * 
 * FUNCIONALIDADES:
 * - Selección individual de items (checkboxes)
 * - Seleccionar todos los items de la página actual
 * - Seleccionar TODOS los items que coinciden con filtros (sin límite)
 * - Excluir items específicos de selección masiva
 * - Cálculo eficiente de cantidad seleccionada
 * - Aplicar selección a queries para operaciones masivas
 * 
 * MODOS DE SELECCIÓN:
 * 1. Individual: Usuario selecciona items uno por uno
 * 2. Página: Selecciona todos los visibles (10, 15, etc.)
 * 3. Global: Selecciona TODOS (incluso 10,000+) con filtros
 * 
 * OPTIMIZACIÓN PARA GRANDES VOLÚMENES:
 * Cuando se seleccionan "todos", NO carga todos los IDs en memoria.
 * En su lugar, usa la query con filtros y excepciones, lo cual es
 * mucho más eficiente para eliminar/actualizar miles de registros.
 * 
 * USO EN COMPONENTE:
 * ```php
 * class GestionarEquipos extends Component
 * {
 *     use WithBulkActions;
 *     
 *     // Las acciones en lote ya están disponibles:
 *     public function deleteSelected()
 *     {
 *         $query = Equipo::query();
 *         $this->applySelectionToQuery($query);
 *         $query->delete(); // Elimina solo los seleccionados
 *     }
 * }
 * ```
 * 
 * USO EN VISTA:
 * ```blade
 * <input wire:model.live="selectAll" type="checkbox">
 * 
 * @foreach($items as $item)
 *     <input wire:model.live="selectedItems" value="{{ $item->id }}" type="checkbox">
 * @endforeach
 * 
 * @if($selectingAll)
 *     Seleccionados: {{ $this->selectedCount }} items
 * @endif
 * ```
 * 
 * REQUISITOS DEL COMPONENTE:
 * - Usar WithPagination de Livewire
 * - Implementar getFilteredQuery(): Retorna query con filtros
 * - Tener propiedad $search para búsqueda
 * - Tener computed property $totalFilteredCount
 * 
 * @package App\Livewire\Traits
 * @since 1.0
 */
trait WithBulkActions
{
    // =======================================================================
    //  PROPIEDADES DE SELECCIÓN
    // =======================================================================
    
    /** @var array<string> IDs de los items seleccionados manualmente */
    public array $selectedItems = [];

    /** @var bool Estado del checkbox "Seleccionar Todo" de la página */
    public bool $selectAll = false;

    /** 
     * @var bool Indica si se están seleccionando TODOS los registros.
     * Cuando es true, NO se cargan todos los IDs en memoria.
     * En su lugar, se usa la query con filtros para máxima eficiencia.
     */
    public bool $selectingAll = false;

    /** 
     * @var array<string> IDs excluidos cuando selectingAll = true.
     * Permite deseleccionar items específicos de una selección masiva.
     */
    public array $exceptItems = [];
    
    // =======================================================================
    //  LIFECYCLE HOOKS
    // =======================================================================

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
