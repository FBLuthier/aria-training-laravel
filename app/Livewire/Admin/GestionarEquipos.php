<?php

namespace App\Livewire\Admin;

use App\Actions\DeleteModelAction;
use App\Actions\ForceDeleteModelAction;
use App\Actions\RestoreModelAction;
use App\Livewire\Forms\EquipoForm;
use App\Livewire\Traits\WithAuditLogging;
use App\Livewire\Traits\WithBulkActions;
use App\Livewire\Traits\WithCrudOperations;
use App\Models\Equipo;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class GestionarEquipos extends Component
{
    use WithPagination, WithBulkActions, WithCrudOperations, WithAuditLogging;
    
    // =======================================================================
    //  CONSTANTES
    // =======================================================================
    
    /** Número de registros por página */
    private const PER_PAGE = 10;
    
    /** Campo de ordenamiento por defecto */
    private const DEFAULT_SORT_FIELD = 'id';
    
    // =======================================================================
    //  PROPIEDADES DE ESTADO Y BÚSQUEDA
    // =======================================================================

    /** @var string Búsqueda principal del componente */
    public string $search = '';

    /** @var array Escucha eventos para refrescar el componente */
    protected $listeners = ['equipoDeleted' => '$refresh', 'equipoRestored' => '$refresh'];

    /** @var ?Equipo Almacena el equipo recién creado para resaltarlo temporalmente */
    public ?Equipo $equipoRecienCreado = null;

    /** @var EquipoForm Formulario reutilizable para crear/editar */
    public EquipoForm $form;

    // =======================================================================
    //  PROPIEDADES PARA ACCIONES EN LOTE
    // =======================================================================

    /** @var bool Confirma eliminación en lote */
    public bool $confirmingBulkDelete = false;
    
    /** @var bool Confirma restauración en lote */
    public bool $confirmingBulkRestore = false;

    /** @var bool Confirma eliminación permanente en lote */
    public bool $confirmingBulkForceDelete = false;

    // =======================================================================
    //  PROPIEDADES PARA SELECCIÓN MÚLTIPLE (BULK ACTIONS)
    // =======================================================================
    // Nota: Estas propiedades están definidas en WithBulkActions trait
    // pero las inicializamos aquí para evitar errores de null

    /** @var array Almacena los IDs de los equipos seleccionados */
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
     */
    public function clearSelections(): void
    {
        $this->selectedItems = [];
        $this->selectAll = false;
    }

    /**
     * Hook que se ejecuta al actualizar la búsqueda.
     * Resetea la paginación y el equipo recién creado.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
        $this->equipoRecienCreado = null;
        $this->clearSelections();
    }

    /**
     * Hook que se ejecuta al cambiar de página.
     */
    public function updatingPage(): void
    {
        $this->equipoRecienCreado = null;
    }

    // =======================================================================
    //  MÉTODOS DE ORDENAMIENTO Y VISTAS
    // =======================================================================


    // =======================================================================
    //  MÉTODOS DEL FORMULARIO (CREAR/EDITAR)
    // =======================================================================

    // =======================================================================
    //  MÉTODOS REQUERIDOS POR WithCrudOperations
    // =======================================================================

    /**
     * Retorna la clase del modelo que maneja este componente.
     */
    protected function getModelClass(): string
    {
        return Equipo::class;
    }

    /**
     * Establece el modelo en el formulario.
     */
    protected function setFormModel($model): void
    {
        $this->form->setEquipo($model);
    }

    /**
     * Realiza la auditoría después de guardar.
     */
    protected function auditFormSave(?array $oldValues): void
    {
        $this->auditSave($this->form->equipo, $oldValues);
    }

    /**
     * Marca un equipo como recién creado.
     */
    protected function markAsRecentlyCreated($model): void
    {
        $this->equipoRecienCreado = $model;
    }

    /**
     * Limpia el marcador de recién creado.
     */
    protected function clearRecentlyCreated(): void
    {
        $this->equipoRecienCreado = null;
    }

    // =======================================================================
    //  MÉTODOS DE ACCIONES EN LOTE
    // =======================================================================

    /**
     * Implementación del método requerido por WithBulkActions.
     * Solo selecciona los IDs de la página actual.
     */
    protected function selectAllItems(): void
    {
        $equipos = Equipo::query()
            ->filtered($this->search, $this->showingTrash, $this->sortField, $this->sortDirection->value)
            ->paginate(self::PER_PAGE);

        $this->selectedItems = $equipos->getCollection()->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();
    }

    /**
     * Obtiene el total de registros filtrados (para el modo selectingAll).
     * 
     * @return int
     */
    #[Computed]
    public function totalFilteredCount(): int
    {
        return Equipo::query()
            ->applyFilters($this->search, $this->showingTrash)
            ->count();
    }

    /**
     * Obtiene la query base con todos los filtros aplicados.
     */
    protected function getFilteredQuery()
    {
        return Equipo::query()->applyFilters($this->search, $this->showingTrash);
    }

    /**
     * Obtiene los modelos seleccionados (para operaciones en lote).
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
     */
    public function confirmDeleteSelected(): void
    {
        $this->confirmingBulkDelete = true;
    }

    /**
     * Ejecuta eliminación en lote (optimizado para grandes volúmenes).
     */
    public function deleteSelected(): void
    {
        $equipos = $this->getSelectedModels();
        
        $result = app(DeleteModelAction::class)->executeBulk($equipos);

        $this->confirmingBulkDelete = false;
        $this->clearSelections();
        $this->dispatch('notify', message: $result['message'], type: 'success');
    }

    /**
     * Confirma restauración en lote.
     */
    public function confirmRestoreSelected(): void
    {
        $this->confirmingBulkRestore = true;
    }

    /**
     * Ejecuta restauración en lote (optimizado para grandes volúmenes).
     */
    public function restoreSelected(): void
    {
        $equipos = $this->getSelectedModels(withTrashed: true);
        
        $result = app(RestoreModelAction::class)->executeBulk($equipos);

        $this->confirmingBulkRestore = false;
        $this->clearSelections();
        $this->dispatch('notify', message: $result['message'], type: 'success');
    }

    /**
     * Confirma eliminación permanente en lote.
     */
    public function confirmForceDeleteSelected(): void
    {
        $this->confirmingBulkForceDelete = true;
    }

    /**
     * Ejecuta eliminación permanente en lote (optimizado para grandes volúmenes).
     */
    public function forceDeleteSelected(): void
    {
        $equipos = $this->getSelectedModels(withTrashed: true);
        
        $result = app(ForceDeleteModelAction::class)->executeBulk($equipos);

        $this->confirmingBulkForceDelete = false;
        $this->clearSelections();
        $this->dispatch('notify', message: $result['message'], type: 'success');
    }

    // =======================================================================
    //  MÉTODO RENDER
    // =======================================================================

    /**
     * Obtiene los equipos paginados con filtros aplicados.
     * 
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    #[Computed]
    public function equipos()
    {
        return Equipo::query()
            ->filtered($this->search, $this->showingTrash, $this->sortField, $this->sortDirection->value)
            ->paginate(self::PER_PAGE);
    }

    /**
     * Renderiza la vista del componente.
     */
    public function render()
    {
        // Verificar autorización para ver la lista de equipos
        $this->authorize('viewAny', Equipo::class);

        return view('livewire.admin.gestionar-equipos');
    }
}