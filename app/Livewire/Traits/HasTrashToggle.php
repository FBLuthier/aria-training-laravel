<?php

namespace App\Livewire\Traits;

use Livewire\WithPagination;

/**
 * =======================================================================
 * TRAIT PARA TOGGLE ENTRE ACTIVOS Y PAPELERA
 * =======================================================================
 * 
 * Este trait implementa la funcionalidad de cambio entre vista de registros
 * activos y vista de papelera (registros eliminados con soft delete).
 * 
 * FUNCIONALIDADES:
 * - toggleTrash(): Cambia entre vista activa ↔ papelera
 * - applyTrashFilter(): Aplica filtro a queries
 * - isShowingTrash(): Verifica estado actual
 * - Reseteo automático de paginación
 * - Limpieza automática de selecciones
 * - Hooks before/after para lógica personalizada
 * 
 * COMPORTAMIENTO:
 * - Vista por defecto: Registros activos (showingTrash = false)
 * - Al hacer toggle: Cambia a papelera (showingTrash = true)
 * - Al volver a hacer toggle: Regresa a activos
 * 
 * IMPORTANTE:
 * Este trait requiere que el modelo use SoftDeletes.
 * Sin SoftDeletes, la papelera estará siempre vacía.
 * 
 * USO EN VISTA:
 * ```blade
 * <x-secondary-button wire:click="toggleTrash">
 *     {{ $showingTrash ? 'Ver Activos' : 'Ver Papelera' }}
 * </x-secondary-button>
 * 
 * @if($showingTrash)
 *     <div class="alert">Mostrando registros eliminados</div>
 * @endif
 * ```
 * 
 * USO EN QUERY:
 * ```php
 * $query = Model::query();
 * $this->applyTrashFilter($query);
 * // Si showingTrash = true: SELECT * FROM table WHERE deleted_at IS NOT NULL
 * // Si showingTrash = false: SELECT * FROM table WHERE deleted_at IS NULL
 * ```
 * 
 * FLUJO COMPLETO:
 * 1. Usuario hace clic en "Ver Papelera"
 * 2. toggleTrash() se ejecuta
 * 3. beforeToggleTrash() hook
 * 4. Se resetea a página 1
 * 5. showingTrash cambia a true
 * 6. Se limpian selecciones
 * 7. afterToggleTrash() hook
 * 8. Vista se recarga mostrando eliminados
 * 
 * @package App\Livewire\Traits
 * @since 1.0
 */
trait HasTrashToggle
{
    // =======================================================================
    //  PROPIEDADES
    // =======================================================================
    
    /** 
     * @var bool Indica si se está mostrando la papelera.
     * false = Registros activos (deleted_at IS NULL)
     * true = Registros eliminados (deleted_at IS NOT NULL)
     */
    public bool $showingTrash = false;

    // =======================================================================
    //  MÉTODO PRINCIPAL
    // =======================================================================

    /**
     * Cambia entre la vista de registros activos y la papelera.
     * 
     * Este método realiza varias acciones:
     * 1. Ejecuta hook beforeToggleTrash()
     * 2. Resetea la paginación a página 1
     * 3. Invierte el estado de showingTrash
     * 4. Limpia selecciones (si el método existe)
     * 5. Ejecuta hook afterToggleTrash()
     * 
     * @return void
     */
    public function toggleTrash(): void
    {
        // Hook antes del cambio
        $this->beforeToggleTrash();
        
        // Resetear a página 1 (WithPagination)
        $this->resetPage();
        
        // Invertir estado
        $this->showingTrash = !$this->showingTrash;
        
        // Limpiar selecciones si el trait WithBulkActions está presente
        if (method_exists($this, 'clearSelections')) {
            $this->clearSelections();
        }
        
        // Hook después del cambio
        $this->afterToggleTrash();
    }

    // =======================================================================
    //  HOOKS PARA PERSONALIZACIÓN
    // =======================================================================

    /**
     * Hook ejecutado ANTES de cambiar entre activos/papelera.
     * 
     * Úsalo para limpiar estado que depende de la vista actual.
     * Por ejemplo, limpiar el registro resaltado.
     * 
     * @return void
     */
    protected function beforeToggleTrash(): void
    {
        // Sobrescribir en el componente si necesitas lógica adicional
        // Ejemplo: $this->clearRecentlyCreated();
    }

    /**
     * Hook ejecutado DESPUÉS de cambiar entre activos/papelera.
     * 
     * Úsalo para lógica adicional después del cambio.
     * 
     * @return void
     */
    protected function afterToggleTrash(): void
    {
        // Sobrescribir en el componente si necesitas lógica adicional
    }

    // =======================================================================
    //  APLICACIÓN A QUERIES
    // =======================================================================

    /**
     * Aplica el filtro de papelera a una query Eloquent.
     * 
     * Si showingTrash = true: Solo registros eliminados (onlyTrashed)
     * Si showingTrash = false: Solo registros activos (query sin cambios)
     * 
     * Uso:
     * ```php
     * $query = Equipo::query();
     * $this->applyTrashFilter($query);
     * // Si showingTrash = true: WHERE deleted_at IS NOT NULL
     * // Si showingTrash = false: WHERE deleted_at IS NULL
     * ```
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query Query a modificar
     * @return \Illuminate\Database\Eloquent\Builder Query con filtro aplicado
     */
    protected function applyTrashFilter($query)
    {
        // Si está mostrando papelera, filtrar solo eliminados
        if ($this->showingTrash) {
            return $query->onlyTrashed();
        }
        
        // Si no, mostrar solo activos (comportamiento por defecto)
        return $query;
    }

    // =======================================================================
    //  MÉTODOS DE UTILIDAD
    // =======================================================================

    /**
     * Verifica si actualmente se está mostrando la papelera.
     * 
     * Útil para lógica condicional en el componente o vista.
     * 
     * Ejemplos:
     * ```php
     * if ($this->isShowingTrash()) {
     *     // Mostrar botones de restaurar
     * }
     * ```
     * 
     * @return bool true si muestra papelera, false si muestra activos
     */
    public function isShowingTrash(): bool
    {
        return $this->showingTrash;
    }
}
