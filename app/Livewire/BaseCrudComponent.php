<?php

namespace App\Livewire;

use App\Actions\DeleteModelAction;
use App\Actions\ForceDeleteModelAction;
use App\Actions\RestoreModelAction;
use App\Livewire\Traits\WithAuditLogging;
use App\Livewire\Traits\WithBulkActions;
use App\Livewire\Traits\WithCrudOperations;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

/**
 * Componente base abstracto para todos los CRUDs del sistema.
 * 
 * Este componente proporciona toda la funcionalidad común de los CRUDs:
 * - Paginación, búsqueda y ordenamiento
 * - Operaciones CRUD básicas (crear, editar, eliminar)
 * - Acciones en lote (bulk actions)
 * - Autorización integrada
 * - Auditoría de cambios
 * - Gestión de papelera (soft deletes)
 * 
 * MODO DE USO:
 * ```php
 * class GestionarEquipos extends BaseCrudComponent
 * {
 *     protected function getModelClass(): string
 *     {
 *         return Equipo::class;
 *     }
 *     
 *     protected function getViewName(): string
 *     {
 *         return 'livewire.admin.gestionar-equipos';
 *     }
 * }
 * ```
 * 
 * BENEFICIOS:
 * - Reduce componentes de ~300 líneas a ~50 líneas
 * - Lógica consistente en todos los CRUDs
 * - Menos errores por duplicación de código
 * - Fácil mantenimiento y actualización
 * 
 * @property-read \Illuminate\Contracts\Pagination\LengthAwarePaginator $items
 * @property-read int $totalFilteredCount
 */
abstract class BaseCrudComponent extends Component
{
    use WithPagination, WithBulkActions, WithCrudOperations, WithAuditLogging;
    
    // =======================================================================
    //  MÉTODOS ABSTRACTOS (DEBEN SER IMPLEMENTADOS POR CLASES HIJAS)
    // =======================================================================
    
    /**
     * Retorna la clase del modelo que maneja este componente.
     * 
     * @return string Nombre completo de la clase del modelo
     */
    abstract protected function getModelClass(): string;
    
    /**
     * Retorna el nombre de la vista Blade del componente.
     * 
     * @return string Nombre de la vista (ej: 'livewire.admin.gestionar-equipos')
     */
    abstract protected function getViewName(): string;
    
    // =======================================================================
    //  MÉTODOS CON IMPLEMENTACIÓN POR DEFECTO (PUEDEN SER SOBRESCRITOS)
    // =======================================================================
    
    /**
     * Retorna el número de registros por página.
     * 
     * @return int
     */
    protected function getPerPage(): int
    {
        return 10;
    }
    
    /**
     * Retorna el campo de ordenamiento por defecto.
     * 
     * @return string
     */
    protected function getDefaultSortField(): string
    {
        return 'id';
    }
    
    /**
     * Retorna el nombre de la propiedad que almacena el modelo recién creado.
     * Usado para resaltar el registro recién creado en la UI.
     * 
     * @return string
     */
    protected function getRecentlyCreatedPropertyName(): string
    {
        // Por defecto: 'equipoRecienCreado', 'ejercicioRecienCreado', etc.
        $modelClass = class_basename($this->getModelClass());
        return lcfirst($modelClass) . 'RecienCreado';
    }
    
    /**
     * Establece el modelo en el formulario.
     * Por defecto asume que el form tiene método setModel().
     * Sobrescribe si tu form usa otro método.
     * 
     * @param Model $model
     * @return void
     */
    protected function setFormModel($model): void
    {
        $this->form->setModel($model);
    }
    
    /**
     * Realiza la auditoría después de guardar.
     * 
     * @param array|null $oldValues
     * @return void
     */
    protected function auditFormSave(?array $oldValues): void
    {
        $this->auditSave($this->form->model, $oldValues);
    }
    
    /**
     * Marca un modelo como recién creado.
     * 
     * @param Model $model
     * @return void
     */
    protected function markAsRecentlyCreated($model): void
    {
        $propertyName = $this->getRecentlyCreatedPropertyName();
        $this->$propertyName = $model;
    }
    
    /**
     * Limpia el marcador de recién creado.
     * 
     * @return void
     */
    protected function clearRecentlyCreated(): void
    {
        $propertyName = $this->getRecentlyCreatedPropertyName();
        $this->$propertyName = null;
    }
    
    // =======================================================================
    //  PROPIEDADES COMUNES
    // =======================================================================
    
    /** @var string Búsqueda principal del componente */
    public string $search = '';
    
    /** @var bool Confirma eliminación en lote */
    public bool $confirmingBulkDelete = false;
    
    /** @var bool Confirma restauración en lote */
    public bool $confirmingBulkRestore = false;
    
    /** @var bool Confirma eliminación permanente en lote */
    public bool $confirmingBulkForceDelete = false;
    
    /** @var array Almacena los IDs seleccionados */
    public array $selectedItems = [];
    
    /** @var bool Controla el estado del checkbox "Seleccionar Todo" */
    public bool $selectAll = false;
    
    /** @var bool Indica si se están seleccionando TODOS los registros */
    public bool $selectingAll = false;
    
    /** @var array IDs excluidos cuando se usa selectingAll */
    public array $exceptItems = [];
    
    // =======================================================================
    //  LIFECYCLE HOOKS
    // =======================================================================
    
    /**
     * Limpia todas las selecciones.
     * 
     * @return void
     */
    public function clearSelections(): void
    {
        $this->selectedItems = [];
        $this->selectAll = false;
        $this->selectingAll = false;
        $this->exceptItems = [];
    }
    
    /**
     * Hook que se ejecuta al actualizar la búsqueda.
     * 
     * @return void
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
        $this->clearRecentlyCreated();
        $this->clearSelections();
    }
    
    /**
     * Hook que se ejecuta al cambiar de página.
     * 
     * @return void
     */
    public function updatingPage(): void
    {
        $this->clearRecentlyCreated();
    }
    
    // =======================================================================
    //  COMPUTED PROPERTIES
    // =======================================================================
    
    /**
     * Obtiene los items paginados con filtros aplicados.
     * 
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    #[Computed]
    public function items()
    {
        $modelClass = $this->getModelClass();
        
        return $modelClass::query()
            ->filtered($this->search, $this->showingTrash, $this->sortField, $this->sortDirection->value)
            ->paginate($this->getPerPage());
    }
    
    /**
     * Obtiene el total de registros filtrados.
     * 
     * @return int
     */
    #[Computed]
    public function totalFilteredCount(): int
    {
        $modelClass = $this->getModelClass();
        
        return $modelClass::query()
            ->applyFilters($this->search, $this->showingTrash)
            ->count();
    }
    
    // =======================================================================
    //  MÉTODOS DE BULK ACTIONS
    // =======================================================================
    
    /**
     * Selecciona todos los items de la página actual.
     * 
     * @return void
     */
    protected function selectAllItems(): void
    {
        $items = $this->items;
        
        $this->selectedItems = $items->getCollection()->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();
    }
    
    /**
     * Obtiene la query base con todos los filtros aplicados.
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getFilteredQuery()
    {
        $modelClass = $this->getModelClass();
        
        return $modelClass::query()->applyFilters($this->search, $this->showingTrash);
    }
    
    /**
     * Obtiene los modelos seleccionados.
     * 
     * @param bool $withTrashed
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getSelectedModels(bool $withTrashed = false)
    {
        $query = $this->getFilteredQuery();
        
        if ($withTrashed) {
            $query->withTrashed();
        }
        
        // Aplicar selección (optimizado para selectingAll)
        if ($this->selectingAll) {
            if (count($this->exceptItems) > 0) {
                $query->whereNotIn('id', $this->exceptItems);
            }
        } else {
            $query->whereIn('id', $this->selectedItems);
        }
        
        return $query->get();
    }
    
    /**
     * Confirma eliminación en lote.
     * 
     * @return void
     */
    public function confirmDeleteSelected(): void
    {
        $this->confirmingBulkDelete = true;
    }
    
    /**
     * Ejecuta eliminación en lote.
     * 
     * @return void
     */
    public function deleteSelected(): void
    {
        $models = $this->getSelectedModels();
        $result = app(DeleteModelAction::class)->executeBulk($models);
        
        $this->confirmingBulkDelete = false;
        $this->clearSelections();
        $this->dispatch('notify', message: $result['message'], type: 'success');
    }
    
    /**
     * Confirma restauración en lote.
     * 
     * @return void
     */
    public function confirmRestoreSelected(): void
    {
        $this->confirmingBulkRestore = true;
    }
    
    /**
     * Ejecuta restauración en lote.
     * 
     * @return void
     */
    public function restoreSelected(): void
    {
        $models = $this->getSelectedModels(withTrashed: true);
        $result = app(RestoreModelAction::class)->executeBulk($models);
        
        $this->confirmingBulkRestore = false;
        $this->clearSelections();
        $this->dispatch('notify', message: $result['message'], type: 'success');
    }
    
    /**
     * Confirma eliminación permanente en lote.
     * 
     * @return void
     */
    public function confirmForceDeleteSelected(): void
    {
        $this->confirmingBulkForceDelete = true;
    }
    
    /**
     * Ejecuta eliminación permanente en lote.
     * 
     * @return void
     */
    public function forceDeleteSelected(): void
    {
        $models = $this->getSelectedModels(withTrashed: true);
        $result = app(ForceDeleteModelAction::class)->executeBulk($models);
        
        $this->confirmingBulkForceDelete = false;
        $this->clearSelections();
        $this->dispatch('notify', message: $result['message'], type: 'success');
    }
    
    // =======================================================================
    //  RENDER
    // =======================================================================
    
    /**
     * Renderiza la vista del componente.
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        // Verificar autorización
        $this->authorize('viewAny', $this->getModelClass());
        
        return view($this->getViewName());
    }
}
