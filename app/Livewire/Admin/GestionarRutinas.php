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

    public \App\Livewire\Forms\RutinaForm $form;

    public $atletas_list = [];
    public $selectedAthlete = '';

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
        $query = Rutina::with(['atleta']);

        // Si es Entrenador, ver rutinas de SUS atletas
        if (auth()->user()->esEntrenador()) {
            $query->whereHas('atleta', function ($q) {
                $q->where('entrenador_id', auth()->id());
            });
        }
        // Si es Atleta, ver SUS rutinas
        elseif (auth()->user()->esAtleta()) {
            $query->where('atleta_id', auth()->id());
        }

        // Filtro por Atleta (Dropdown)
        if ($this->selectedAthlete) {
            $query->where('atleta_id', $this->selectedAthlete);
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
    }

    public function toggleActive($rutinaId)
    {
        $rutina = Rutina::find($rutinaId);
        
        if (!$rutina) return;

        // Si ya está activa, no hacemos nada (o podríamos desactivarla si se permite ninguna activa)
        // El requerimiento dice "que esa rutina activa sea la que vea el atleta", implicando que siempre debe haber una (o ninguna).
        // Vamos a permitir desactivar si se hace click en la activa.
        
        if ($rutina->estado) {
            $rutina->update(['estado' => 0]);
        } else {
            // Desactivar todas las otras rutinas de este atleta
            Rutina::where('atleta_id', $rutina->atleta_id)
                  ->where('id', '!=', $rutinaId)
                  ->update(['estado' => 0]);

            // Activar esta
            $rutina->update(['estado' => 1]);
        }
    }
}
