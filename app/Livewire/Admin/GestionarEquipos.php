<?php

namespace App\Livewire\Admin;

use App\Enums\SortDirection;
use App\Models\Equipo;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Livewire\Traits\WithModalManagement;
use App\Livewire\Traits\WithBulkActions;
use App\Livewire\Traits\WithCustomPagination;
use Illuminate\Validation\Rule;

#[Layout('layouts.app')]
class GestionarEquipos extends Component
{
    use WithPagination, WithModalManagement, WithBulkActions, WithCustomPagination;

    // =======================================================================
    //  PROPIEDADES DE ESTADO Y BÚSQUEDA
    // =======================================================================
    public string $search = '';
    public string $sortField = 'id';
    public SortDirection $sortDirection = SortDirection::ASC;
    public bool $showingTrash = false;
    public ?Equipo $equipoRecienCreado = null;

    // =======================================================================
    //  PROPIEDADES PARA EL MODAL UNIFICADO
    // =======================================================================
    
    /**
     * @var bool Controla la visibilidad del modal de creación/edición.
     */
    public bool $showModal = false;

    /**
     * @var Equipo Instancia del modelo Equipo que se está creando o editando.
     * Funciona como un "Form Object" para vincular datos en el formulario del modal.
     */
    public Equipo $form;

    // =======================================================================
    //  LISTENERS Y HOOKS DEL CICLO DE VIDA
    // =======================================================================
    protected $listeners = ['equipoDeleted' => '$refresh', 'equipoRestored' => '$refresh'];
    
    /**
     * Hook que se ejecuta cuando se inicializa el componente.
     * Prepara el 'Form Object' con una instancia vacía de Equipo.
     */
    public function mount(): void
    {
        $this->form = new Equipo();
    }
    
    /**
     * Hook que se ejecuta antes de cambiar de página de paginación.
     * Limpia el resaltado del equipo recién creado/actualizado.
     */
    public function updatingPage(): void 
    {
        $this->equipoRecienCreado = null;
    }

    // =======================================================================
    //  REGLAS DE VALIDACIÓN PARA EL FORMULARIO
    // =======================================================================

    /**
     * Define las reglas de validación para el formulario.
     * Se aplican a la propiedad `$form`.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'form.nombre' => [
                'required',
                'string',
                'min:3',
                'max:255',
                // Regla 'unique' que ignora el ID del propio modelo al editar.
                Rule::unique('equipos', 'nombre')->ignore($this->form->id),
            ],
        ];
    }

    // =======================================================================
    //  MÉTODOS DE MANIPULACIÓN DE VISTAS Y ORDENAMIENTO
    // =======================================================================
    public function toggleTrash(): void
    {
        $this->equipoRecienCreado = null;
        $this->resetPage();
        $this->showingTrash = !$this->showingTrash;
    }

    public function sortBy(string $field): void
    {
        $this->equipoRecienCreado = null;
        $this->resetPage();
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection->opposite();
        } else {
            $this->sortDirection = SortDirection::ASC;
        }
        $this->sortField = $field;
    }
    
    // =======================================================================
    //  MÉTODOS PARA EL MODAL UNIFICADO (CREAR Y EDITAR)
    // =======================================================================

    /**
     * Prepara el modal para crear un nuevo equipo.
     */
    public function crear(): void
    {
        $this->form = new Equipo(); // Prepara un modelo vacío
        $this->resetErrorBag();
        $this->showModal = true;
    }

    /**
     * Prepara el modal para editar un equipo existente.
     *
     * @param Equipo $equipo El equipo a editar, inyectado por Livewire.
     */
    public function editar(Equipo $equipo): void
    {
        $this->form = $equipo; // Carga el modelo existente en el formulario
        $this->resetErrorBag();
        $this->showModal = true;
    }

    /**
     * Guarda el equipo (tanto para creación como para edición).
     * El método `save` del modelo Eloquent se encarga de diferenciar
     * entre `INSERT` y `UPDATE` basado en si el modelo existe.
     */
    public function save(): void
    {
        $this->validate();
        
        $this->form->save();

        $this->equipoRecienCreado = $this->form; // Resalta la fila en la tabla
        $this->showModal = false;
    }
    
    // =======================================================================
    //  MÉTODOS DE ACCIÓN (Llamados por el Trait de Modales de Confirmación)
    // =======================================================================
    public function deleteEquipo(): void
    {
        if ($this->modalConfirmingId) {
            Equipo::find($this->modalConfirmingId)->delete();
            $this->dispatch('equipoDeleted');
            $this->cancelAction();
        }
    }
    
    public function restoreEquipo(): void
    {
        if ($this->modalConfirmingId) {
            Equipo::withTrashed()->find($this->modalConfirmingId)->restore();
            $this->dispatch('equipoRestored');
            $this->cancelAction();
        }
    }

    public function forceDeleteEquipo(): void
    {
        if ($this->modalConfirmingId) {
            Equipo::withTrashed()->find($this->modalConfirmingId)->forceDelete();
            $this->dispatch('$refresh');
            $this->cancelAction();
        }
    }
    
    // =======================================================================
    //  MÉTODO RENDER
    // =======================================================================
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