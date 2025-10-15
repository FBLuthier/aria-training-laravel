<?php

namespace App\Livewire\Admin;

use App\Enums\SortDirection;
use App\Livewire\Forms\EquipoForm;
use App\Livewire\Traits\WithBulkActions;
use App\Models\Equipo;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class GestionarEquipos extends Component
{
    use WithPagination, WithBulkActions;
    // =======================================================================
    //  PROPIEDADES DE ESTADO Y BÚSQUEDA
    // =======================================================================

    /** @var string Búsqueda principal del componente */
    public string $search = '';

    /** @var array Escucha eventos para refrescar el componente */
    protected $listeners = ['equipoDeleted' => '$refresh', 'equipoRestored' => '$refresh'];

    /**
     * Listener para detectar cambios en showFormModal desde Alpine.
     * Este método se ejecuta automáticamente cuando Alpine cambia la propiedad.
     */
    public function updatedShowFormModal($value): void
    {
        if (!$value) {
            // Si Alpine cerró el modal, asegurémonos de que esté realmente cerrado
            $this->form->reset();
            $this->equipoRecienCreado = null;
        }
    }

    /** @var string Columna por la que se ordena la tabla */
    public string $sortField = 'id';

    /** @var SortDirection Dirección del ordenamiento */
    public SortDirection $sortDirection = SortDirection::ASC;

    /** @var bool Controla la visibilidad de la papelera */
    public bool $showingTrash = false;
    
    /** @var ?Equipo Almacena el equipo recién creado para resaltarlo temporalmente */
    public ?Equipo $equipoRecienCreado = null;

    // =======================================================================
    //  PROPIEDADES PARA MODALES Y FORMULARIOS
    // =======================================================================

    /** @var EquipoForm Formulario reutilizable para crear/editar */
    public EquipoForm $form;

    /** @var bool Controla la visibilidad del modal de formulario */
    public bool $showFormModal = false;
    
    /** @var ?int ID del equipo a eliminar (soft delete) */
    public ?int $deletingId = null;

    /** @var ?int ID del equipo a restaurar */
    public ?int $restoringId = null;

    /** @var ?int ID del equipo a eliminar permanentemente */
    public ?int $forceDeleteingId = null;

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

    /**
     * Cambia entre la vista de equipos activos y la papelera.
     */
    public function toggleTrash(): void
    {
        $this->equipoRecienCreado = null;
        $this->resetPage();
        $this->showingTrash = !$this->showingTrash;
        $this->clearSelections();
    }

    /**
     * Cambia la columna y dirección del ordenamiento.
     */
    public function sortBy(string $field): void
    {
        $this->equipoRecienCreado = null;
        
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection->opposite();
        } else {
            $this->sortDirection = SortDirection::ASC;
        }
        
        $this->sortField = $field;
    }

    // =======================================================================
    //  MÉTODOS DEL FORMULARIO (CREAR/EDITAR)
    // =======================================================================

    /**
     * Abre el modal para crear un nuevo equipo.
     */
    public function create(): void
    {
        $this->form->reset();
        $this->showFormModal = true;
        $this->equipoRecienCreado = null;
    }

    /**
     * Abre el modal para editar un equipo existente.
     */
    public function edit(int $equipoId): void
    {
        $equipo = Equipo::findOrFail($equipoId);
        $this->form->setEquipo($equipo);
        $this->showFormModal = true;
    }

    /**
     * Guarda el equipo (crear o actualizar).
     */
    public function save(): void
    {
        $message = $this->form->save();

        // Si es un equipo nuevo, lo guardamos para resaltarlo
        if (!$this->form->equipo->wasRecentlyCreated) {
            $this->equipoRecienCreado = null;
        } else {
            $this->equipoRecienCreado = $this->form->equipo;
        }

        $this->closeFormModal();
        $this->dispatch('notify', message: $message, type: 'success');
    }

    /**
     * Cierra el modal del formulario.
     */
    public function closeFormModal(): void
    {
        $this->showFormModal = false;
        $this->form->reset();
    }

    /**
     * Método genérico para cerrar cualquier modal desde Alpine.
     * Este método es llamado por Alpine cuando el usuario cierra el modal.
     */
    public function closeModal(string $modalProperty): void
    {
        switch ($modalProperty) {
            case 'showFormModal':
                $this->showFormModal = false;
                $this->form->reset();
                $this->equipoRecienCreado = null;
                break;
            case 'deletingId':
                $this->deletingId = null;
                break;
            case 'restoringId':
                $this->restoringId = null;
                break;
            case 'forceDeleteingId':
                $this->forceDeleteingId = null;
                break;
            case 'confirmingBulkDelete':
                $this->confirmingBulkDelete = false;
                break;
            case 'confirmingBulkRestore':
                $this->confirmingBulkRestore = false;
                break;
            case 'confirmingBulkForceDelete':
                $this->confirmingBulkForceDelete = false;
                break;
        }
    }

    // =======================================================================
    //  MÉTODOS DE ELIMINACIÓN INDIVIDUAL
    // =======================================================================

    /**
     * Confirma la eliminación de un equipo (soft delete).
     */
    public function delete(int $id): void
    {
        $this->deletingId = $id;
    }

    /**
     * Ejecuta la eliminación suave del equipo.
     */
    public function performDelete(): void
    {
        if ($this->deletingId) {
            Equipo::findOrFail($this->deletingId)->delete();
            $this->deletingId = null;
            $this->dispatch('notify', message: 'Equipo enviado a la papelera.', type: 'success');
        }
    }

    /**
     * Confirma la restauración de un equipo.
     */
    public function restore(int $id): void
    {
        $this->restoringId = $id;
    }

    /**
     * Ejecuta la restauración del equipo.
     */
    public function performRestore(): void
    {
        if ($this->restoringId) {
            Equipo::withTrashed()->findOrFail($this->restoringId)->restore();
            $this->restoringId = null;
            $this->dispatch('notify', message: 'Equipo restaurado exitosamente.', type: 'success');
        }
    }

    /**
     * Confirma la eliminación permanente de un equipo.
     */
    public function forceDelete(int $id): void
    {
        $this->forceDeleteingId = $id;
    }

    /**
     * Ejecuta la eliminación permanente del equipo.
     */
    public function performForceDelete(): void
    {
        if ($this->forceDeleteingId) {
            Equipo::withTrashed()->findOrFail($this->forceDeleteingId)->forceDelete();
            $this->forceDeleteingId = null;
            $this->dispatch('notify', message: 'Equipo eliminado permanentemente.', type: 'error');
        }
    }

    // =======================================================================
    //  MÉTODOS DE ACCIONES EN LOTE
    // =======================================================================

    /**
     * Implementación del método requerido por WithBulkActions.
     */
    protected function selectAllItems(): void
    {
        $this->selectedItems = Equipo::query()
            ->when($this->search, fn($q) => $q->where('nombre', 'like', '%' . $this->search . '%'))
            ->when($this->showingTrash, fn($q) => $q->onlyTrashed())
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();
    }

    /**
     * Confirma eliminación en lote.
     */
    public function confirmDeleteSelected(): void
    {
        $this->confirmingBulkDelete = true;
    }

    /**
     * Ejecuta eliminación en lote.
     */
    public function deleteSelected(): void
    {
        Equipo::whereIn('id', $this->selectedItems)->delete();
        $this->confirmingBulkDelete = false;
        $this->clearSelections();
        $this->dispatch('notify', message: 'Equipos enviados a la papelera.', type: 'success');
    }

    /**
     * Confirma restauración en lote.
     */
    public function confirmRestoreSelected(): void
    {
        $this->confirmingBulkRestore = true;
    }

    /**
     * Ejecuta restauración en lote.
     */
    public function restoreSelected(): void
    {
        Equipo::withTrashed()->whereIn('id', $this->selectedItems)->restore();
        $this->confirmingBulkRestore = false;
        $this->clearSelections();
        $this->dispatch('notify', message: 'Equipos restaurados exitosamente.', type: 'success');
    }

    /**
     * Confirma eliminación permanente en lote.
     */
    public function confirmForceDeleteSelected(): void
    {
        $this->confirmingBulkForceDelete = true;
    }

    /**
     * Ejecuta eliminación permanente en lote.
     */
    public function forceDeleteSelected(): void
    {
        Equipo::withTrashed()->whereIn('id', $this->selectedItems)->forceDelete();
        $this->confirmingBulkForceDelete = false;
        $this->clearSelections();
        $this->dispatch('notify', message: 'Equipos eliminados permanentemente.', type: 'error');
    }

    // =======================================================================
    //  MÉTODO RENDER
    // =======================================================================

    /**
     * Renderiza la vista del componente.
     */
    public function render()
    {
        $equipos = Equipo::query()
            ->when($this->search, fn($query) => $query->where('nombre', 'like', '%' . $this->search . '%'))
            ->when($this->showingTrash, fn($query) => $query->onlyTrashed())
            ->orderBy($this->sortField, $this->sortDirection->value)
            ->paginate(10);

        return view('livewire.admin.gestionar-equipos', [
            'equipos' => $equipos,
        ]);
    }
}