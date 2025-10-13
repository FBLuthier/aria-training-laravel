<?php

namespace App\Livewire\Traits;

use App\Models\Equipo; // Importamos el modelo con el que vamos a trabajar.

/**
 * =========================================================================
 * TRAIT PARA LA GESTIÓN DE ACCIONES EN LOTE (BULK ACTIONS)
 * =========================================================================
 * Este Trait encapsula toda la funcionalidad relacionada con la selección
 * múltiple de registros y la ejecución de acciones sobre ellos.
 * Al usar este Trait, cualquier componente de Livewire adquiere
 * instantáneamente la capacidad de manejar acciones en lote.
 */
trait WithBulkActions
{
    // =======================================================================
    //  PROPIEDADES DEL TRAIT
    // =======================================================================

    /** @var array Almacena los IDs de los registros seleccionados. */
    public array $selectedEquipos = [];

    /** @var bool Controla el estado del checkbox "Seleccionar Todo". */
    public bool $selectAll = false;

    /** @var bool Controla la visibilidad del modal de confirmación de borrado en lote. */
    public bool $confirmingBulkDelete = false;
    
    /** @var bool Controla la visibilidad del modal de confirmación de restauración en lote. */
    public bool $confirmingBulkRestore = false;

    /** @var bool Controla la visibilidad del modal de confirmación de borrado forzado en lote. */
    public bool $confirmingBulkForceDelete = false;


    // =======================================================================
    //  LIFECYCLE HOOKS DEL TRAIT
    // =======================================================================

    /**
     * Gestiona la lógica de "Seleccionar Todo". Se activa cuando la propiedad $selectAll cambia.
     *
     * @param bool $value El nuevo valor de $selectAll
     */
    public function updatedSelectAll($value)
    {
        if ($value) {
            // Construye la consulta base, respetando la vista actual (activos o papelera) y la búsqueda.
            $query = Equipo::query()
                ->when($this->showingTrash, fn($q) => $q->onlyTrashed())
                ->when($this->search, fn($q) => $q->where('nombre', 'like', '%' . $this->search . '%'));

            // Llena el array de seleccionados con los IDs de la página actual.
            $this->selectedEquipos = $query->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            // Si se desmarca, vacía el array.
            $this->selectedEquipos = [];
        }
    }


    // =======================================================================
    //  MÉTODOS DEL TRAIT
    // =======================================================================

    public function confirmDeleteSelected()
    {
        $this->confirmingBulkDelete = true;
    }

    public function deleteSelected()
    {
        Equipo::whereIn('id', $this->selectedEquipos)->delete();
        $this->resetBulkSelection(); // Resetea la UI
        $this->dispatch('equipoDeleted');
    }

    public function confirmRestoreSelected()
    {
        $this->confirmingBulkRestore = true;
    }

    public function restoreSelected()
    {
        Equipo::whereIn('id', $this->selectedEquipos)->withTrashed()->restore();
        $this->resetBulkSelection(); // Resetea la UI
        $this->dispatch('equipoRestored');
    }

    public function confirmForceDeleteSelected()
    {
        $this->confirmingBulkForceDelete = true;
    }

    public function forceDeleteSelected()
    {
        Equipo::whereIn('id', $this->selectedEquipos)->withTrashed()->forceDelete();
        $this->resetBulkSelection(); // Resetea la UI
        $this->dispatch('$refresh');
    }
    
    /**
     * Método helper para limpiar el estado de la selección en lote.
     */
    public function resetBulkSelection()
    {
        $this->confirmingBulkDelete = false;
        $this->confirmingBulkRestore = false;
        $this->confirmingBulkForceDelete = false;
        $this->selectedEquipos = [];
        $this->selectAll = false;
    }
}