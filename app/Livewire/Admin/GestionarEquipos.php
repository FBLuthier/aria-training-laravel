<?php

namespace App\Livewire\Admin;

use App\Models\Equipo;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class GestionarEquipos extends Component
{
    use WithPagination;

    // =======================================================================
    //  PROPIEDADES DE ESTADO Y BÚSQUEDA
    // =======================================================================

    /** @var string Búsqueda principal del componente */
    public string $search = '';

    /** @var string Columna por la que se ordena la tabla */
    public string $sortField = 'id';

    /** @var string Dirección del ordenamiento (asc o desc) */
    public string $sortDirection = 'asc';

    /** @var bool Controla la visibilidad de la papelera */
    public bool $showingTrash = false;
    
    /** @var ?Equipo Almacena el equipo recién creado para resaltarlo temporalmente */
    public ?Equipo $equipoRecienCreado = null;


    // =======================================================================
    //  PROPIEDADES PARA MODALES
    // =======================================================================

    /** @var bool Controla la visibilidad del modal de creación */
    public bool $showingCrearModal = false;

    /** @var ?Equipo Almacena el modelo a editar para el modal de edición */
    public ?Equipo $equipoParaEditar = null;
    
    /** @var ?int Almacena el ID del equipo para el modal de borrado suave */
    public ?int $equipoParaEliminarId = null;

    /** @var ?int Almacena el ID del equipo para el modal de restauración */
    public ?int $equipoParaRestaurarId = null;

    /** @var ?int Almacena el ID del equipo para el modal de borrado forzado */
    public ?int $equipoParaBorradoForzadoId = null;


    // =======================================================================
    //  PROPIEDADES PARA ACCIONES EN LOTE (BULK ACTIONS)
    // =======================================================================

    /** @var array Almacena los IDs de los equipos seleccionados */
    public array $selectedEquipos = [];

    /** @var bool Controla el estado del checkbox "Seleccionar Todo" */
    public bool $selectAll = false;

    /** @var bool Controla la visibilidad del modal de confirmación de borrado en lote */
    public bool $confirmingBulkDelete = false;
    
    /** @var bool Controla la visibilidad del modal de confirmación de restauración en lote */
    public bool $confirmingBulkRestore = false;

    /** @var bool Controla la visibilidad del modal de confirmación de borrado forzado en lote */
    public bool $confirmingBulkForceDelete = false;


    // =======================================================================
    //  PROPIEDADES DEL FORMULARIO (para data-binding)
    // =======================================================================

    /** @var string Vinculada al campo 'nombre' del formulario de creación */
    public string $nombreNuevoEquipo = '';

    /** @var string Vinculada al campo 'nombre' del formulario de edición */
    public string $nombreEquipoEnEdicion = '';


    // =======================================================================
    //  LISTENERS DE EVENTOS
    // =======================================================================
    
    /** @var array Escucha eventos para refrescar el componente */
    protected $listeners = ['equipoDeleted' => '$refresh', 'equipoRestored' => '$refresh'];


    // =======================================================================
    //  REGLAS DE VALIDACIÓN
    // =======================================================================

    /**
     * Define las reglas de validación para los formularios de creación y edición.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'nombreEquipoEnEdicion' => 'required|string|min:3|max:255',
            'nombreNuevoEquipo' => 'required|string|min:3|max:255|unique:equipos,nombre',
        ];
    }


    // =======================================================================
    //  LIFECYCLE HOOKS (Se ejecutan en respuesta a actualizaciones)
    // =======================================================================
    
    /**
     * Resetea el equipo recién creado cuando el usuario busca, para que el resaltado desaparezca.
     */
    public function updatingSearch(): void
    {
        $this->equipoRecienCreado = null;
    }

    /**
     * Resetea el equipo recién creado cuando el usuario cambia de página.
     */
    public function updatingPage(): void
    {
        $this->equipoRecienCreado = null;
    }

    /**
     * Gestiona la lógica de "Seleccionar Todo". Se activa cuando la propiedad $selectAll cambia.
     *
     * @param bool $value El nuevo valor de $selectAll
     */
    public function updatedSelectAll($value)
    {
        if ($value) {
            $query = Equipo::query()
                ->when($this->showingTrash, fn($q) => $q->onlyTrashed())
                ->when($this->search, fn($q) => $q->where('nombre', 'like', '%' . $this->search . '%'));

            $this->selectedEquipos = $query->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedEquipos = [];
        }
    }


    // =======================================================================
    //  MÉTODOS DE MANIPULACIÓN DE VISTAS Y ORDENAMIENTO
    // =======================================================================

    /**
     * Cambia entre la vista de equipos activos y la papelera.
     */
    public function toggleTrash(): void
    {
        $this->equipoRecienCreado = null;
        $this->resetPage();
        $this->showingTrash = !$this->showingTrash;
    }

    /**
     * Cambia la columna y la dirección del ordenamiento.
     *
     * @param string $field La columna por la que ordenar
     */
    public function sortBy(string $field): void
    {
        $this->equipoRecienCreado = null;
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }


    // =======================================================================
    //  MÉTODOS PARA MODAL DE CREACIÓN
    // =======================================================================

    public function crearEquipo(): void
    {
        $this->showingCrearModal = true;
        $this->equipoRecienCreado = null;
    }

    public function cancelarCreacion(): void
    {
        $this->showingCrearModal = false;
        $this->reset('nombreNuevoEquipo');
    }

    public function storeEquipo(): void
    {
        $validated = $this->validate(['nombreNuevoEquipo' => 'required|string|min:3|max:255|unique:equipos,nombre']);
        $equipo = Equipo::create(['nombre' => $validated['nombreNuevoEquipo']]);
        $this->equipoRecienCreado = $equipo;
        $this->cancelarCreacion();
    }


    // =======================================================================
    //  MÉTODOS PARA MODAL DE EDICIÓN
    // =======================================================================
    
    public function editarEquipo(Equipo $equipo): void
    {
        $this->equipoParaEditar = $equipo;
        $this->nombreEquipoEnEdicion = $equipo->nombre;
    }

    public function cancelarEdicion(): void
    {
        $this->equipoParaEditar = null;
        $this->reset('nombreEquipoEnEdicion');
    }

    public function updateEquipo(): void
    {
        $validated = $this->validate(['nombreEquipoEnEdicion' => 'required|string|min:3|max:255']);
        $this->equipoParaEditar->nombre = $validated['nombreEquipoEnEdicion'];
        $this->equipoParaEditar->save();
        $this->cancelarEdicion();
    }


    // =======================================================================
    //  MÉTODOS PARA MODALES DE ACCIONES INDIVIDUALES (Eliminar, Restaurar, etc.)
    // =======================================================================

    public function confirmarEliminacion(int $id): void
    {
        $this->equipoParaEliminarId = $id;
    }

    public function cancelarEliminacion(): void
    {
        $this->equipoParaEliminarId = null;
    }

    public function deleteEquipo(): void
    {
        if ($this->equipoParaEliminarId) {
            Equipo::find($this->equipoParaEliminarId)->delete();
            $this->cancelarEliminacion();
            $this->dispatch('equipoDeleted');
        }
    }
    
    public function confirmarRestauracion(int $id): void
    {
        $this->equipoParaRestaurarId = $id;
    }

    public function cancelarRestauracion(): void
    {
        $this->equipoParaRestaurarId = null;
    }
    
    public function restoreEquipo(): void
    {
        if ($this->equipoParaRestaurarId) {
            Equipo::withTrashed()->find($this->equipoParaRestaurarId)->restore();
            $this->cancelarRestauracion(); // Cierra el modal y resetea
            $this->dispatch('equipoRestored');
        }
    }

    public function confirmarBorradoForzado(int $id): void
    {
        $this->equipoParaBorradoForzadoId = $id;
    }
    
    public function cancelarBorradoForzado(): void
    {
        $this->equipoParaBorradoForzadoId = null;
    }

    public function forceDeleteEquipo(): void
    {
        if ($this->equipoParaBorradoForzadoId) {
            Equipo::withTrashed()->find($this->equipoParaBorradoForzadoId)->forceDelete();
            $this->cancelarBorradoForzado(); // Cierra el modal y resetea
            $this->dispatch('$refresh');
        }
    }

    
    // =======================================================================
    //  MÉTODOS PARA ACCIONES EN LOTE (BULK ACTIONS)
    // =======================================================================

    public function confirmDeleteSelected()
    {
        $this->confirmingBulkDelete = true;
    }

    public function deleteSelected()
    {
        Equipo::whereIn('id', $this->selectedEquipos)->delete();
        $this->confirmingBulkDelete = false;
        $this->selectedEquipos = [];
        $this->selectAll = false;
        $this->dispatch('equipoDeleted');
    }

    public function confirmRestoreSelected()
    {
        $this->confirmingBulkRestore = true;
    }

    public function restoreSelected()
    {
        Equipo::whereIn('id', $this->selectedEquipos)->withTrashed()->restore();
        $this->confirmingBulkRestore = false;
        $this->selectedEquipos = [];
        $this->selectAll = false;
        $this->dispatch('equipoRestored');
    }

    public function confirmForceDeleteSelected()
    {
        $this->confirmingBulkForceDelete = true;
    }

    public function forceDeleteSelected()
    {
        Equipo::whereIn('id', $this->selectedEquipos)->withTrashed()->forceDelete();
        $this->confirmingBulkForceDelete = false;
        $this->selectedEquipos = [];
        $this->selectAll = false;
        $this->dispatch('$refresh');
    }


    // =======================================================================
    //  MÉTODO RENDER (Renderiza el componente)
    // =======================================================================

    /**
     * Renderiza la vista del componente con los datos necesarios.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $equipos = Equipo::query()
            ->when($this->search, fn($query) => $query->where('nombre', 'like', '%' . $this->search . '%'))
            ->when($this->showingTrash, fn($query) => $query->onlyTrashed())
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.gestionar-equipos', [
            'equipos' => $equipos,
        ]);
    }
}