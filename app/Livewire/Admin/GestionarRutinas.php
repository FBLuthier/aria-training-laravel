<?php

namespace App\Livewire\Admin;

use App\Livewire\BaseCrudComponent;
use App\Models\Rutina;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Computed;

/**
 * Componente para gestionar Rutinas (Maestro).
 * 
 * Permite:
 * 1. Listar rutinas (filtradas por atleta si es entrenador).
 * 2. Crear/Editar rutinas (Nombre, Descripción, Atleta asignado).
 * 3. Navegar al detalle de la rutina (Calendario).
 */
#[Layout('layouts.app')]
class GestionarRutinas extends BaseCrudComponent
{
    // =======================================================================
    //  PROPIEDADES
    // =======================================================================

    #[Rule('required|min:3|max:45')]
    public $nombre = '';

    #[Rule('nullable|string')]
    public $descripcion = '';

    #[Rule('required|exists:usuarios,id')]
    public $usuario_id = ''; // El atleta al que se asigna

    #[Rule('required|exists:objetivos,id')]
    public $objetivo_id = '';

    public $atletas_list = [];
    public $objetivos_list = [];

    public $editingId = null;

    // =======================================================================
    //  MÉTODOS BASE
    // =======================================================================

    protected function getModelClass(): string
    {
        return Rutina::class;
    }

    protected function getViewName(): string
    {
        return 'livewire.admin.gestionar-rutinas';
    }

    #[Computed]
    public function items()
    {
        $query = Rutina::query();

        // Si es Entrenador, ver rutinas de SUS atletas
        if (auth()->user()->esEntrenador()) {
            $query->whereHas('usuario', function ($q) {
                $q->where('entrenador_id', auth()->id());
            });
        }
        // Si es Atleta, ver SUS rutinas
        elseif (auth()->user()->esAtleta()) {
            $query->where('usuario_id', auth()->id());
        }

        // Filtros estándar
        if ($this->search) {
            $query->where('nombre', 'like', '%' . $this->search . '%');
        }

        if ($this->showingTrash) {
            $query->onlyTrashed();
        }

        return $query->orderBy($this->sortField, $this->sortDirection->value)
                     ->paginate($this->getPerPage());
    }

    // =======================================================================
    //  CICLO DE VIDA
    // =======================================================================

    public function mount()
    {
        // Cargar listas
        if (auth()->user()->esEntrenador()) {
            $this->atletas_list = User::where('entrenador_id', auth()->id())->get();
        } else {
            // Admin ve todos los atletas (o lógica a definir)
            $this->atletas_list = User::where('tipo_usuario_id', 3)->get();
        }

        $this->objetivos_list = \App\Models\Objetivo::all();
    }

    // =======================================================================
    //  ACCIONES
    // =======================================================================

    public function create(): void
    {
        $this->reset(['nombre', 'descripcion', 'usuario_id', 'objetivo_id']);
        $this->showFormModal = true;
    }

    public function edit(int $id): void
    {
        $this->reset(['nombre', 'descripcion', 'usuario_id', 'objetivo_id']);
        $model = Rutina::findOrFail($id);
        
        $this->authorize('update', $model);

        $this->editingId = $id;
        $this->nombre = $model->nombre;
        $this->descripcion = $model->descripcion; // Nota: Rutina no tiene descripcion en migration original, verificar
        $this->usuario_id = $model->usuario_id;
        $this->objetivo_id = $model->objetivo_id;

        $this->showFormModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'nombre' => $this->nombre,
            'usuario_id' => $this->usuario_id,
            'objetivo_id' => $this->objetivo_id,
            'estado' => 1, // Activa por defecto
        ];

        if ($this->editingId) {
            $model = Rutina::findOrFail($this->editingId);
            $this->authorize('update', $model);
            $model->update($data);
            $this->dispatch('notify', message: 'Rutina actualizada correctamente', type: 'success');
        } else {
            Rutina::create($data);
            $this->dispatch('notify', message: 'Rutina creada correctamente', type: 'success');
        }

        $this->showFormModal = false;
    }
}
