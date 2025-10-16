<?php

namespace App\Livewire\Traits;

use Livewire\WithPagination;

/**
 * Trait para manejar toggle entre vista activa y papelera.
 * 
 * Este trait proporciona la funcionalidad estándar para:
 * - Cambiar entre registros activos y eliminados (papelera)
 * - Resetear paginación al cambiar
 * - Limpiar selecciones al cambiar
 * 
 * REQUISITOS:
 * - El componente debe usar WithPagination
 * - El modelo debe tener SoftDeletes
 * - Debe tener propiedad: $showingTrash
 */
trait HasTrashToggle
{
    /** @var bool Controla la visibilidad de la papelera */
    public bool $showingTrash = false;

    /**
     * Cambia entre la vista de registros activos y la papelera.
     */
    public function toggleTrash(): void
    {
        $this->beforeToggleTrash();
        
        $this->resetPage();
        $this->showingTrash = !$this->showingTrash;
        
        if (method_exists($this, 'clearSelections')) {
            $this->clearSelections();
        }
        
        $this->afterToggleTrash();
    }

    /**
     * Hook que se ejecuta antes de cambiar la vista.
     * Puede ser sobrescrito para limpiar estado.
     */
    protected function beforeToggleTrash(): void
    {
        // Override en el componente si necesitas esta funcionalidad
    }

    /**
     * Hook que se ejecuta después de cambiar la vista.
     * Puede ser sobrescrito para lógica adicional.
     */
    protected function afterToggleTrash(): void
    {
        // Override en el componente si necesitas esta funcionalidad
    }

    /**
     * Aplica el filtro de papelera a una query.
     */
    protected function applyTrashFilter($query)
    {
        if ($this->showingTrash) {
            return $query->onlyTrashed();
        }
        
        return $query;
    }

    /**
     * Verifica si está mostrando la papelera.
     */
    public function isShowingTrash(): bool
    {
        return $this->showingTrash;
    }
}
