<?php

namespace App\Livewire\Traits;

/**
 * =========================================================================
 * TRAIT PARA LA GESTIÓN AUTOMÁTICA DE LA PAGINACIÓN
 * =========================================================================
 * Este Trait utiliza los "Lifecycle Hooks" de Livewire para resetear
 * automáticamente la paginación cuando cambian ciertas propiedades
 * que actúan como filtros (ej. la búsqueda).
 *
 * Esto evita la necesidad de llamar manualmente a `$this->resetPage()`
 * en múltiples lugares, siguiendo el principio DRY.
 */
trait WithCustomPagination
{
    /**
     * Este es un "Lifecycle Hook" de Livewire.
     * El nombre `updatingSearch` le dice a Livewire que ejecute este método
     * automáticamente CADA VEZ que la propiedad `$search` esté a punto de cambiar,
     * pero antes de que se actualice.
     *
     * Al poner `$this->resetPage()` aquí, nos aseguramos de que siempre que el
     * usuario escriba algo en la barra de búsqueda, la paginación volverá a la página 1.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // En el futuro, si añadiéramos más filtros como, por ejemplo,
    // public $filtroPorEstado = 'activo';
    // simplemente añadiríamos otro hook aquí:
    //
    // public function updatingFiltroPorEstado(): void
    // {
    //     $this->resetPage();
    // }
    //
    // ¡Y funcionaría automáticamente!
}