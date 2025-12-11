<?php

namespace App\Livewire\Admin;

use App\Livewire\BaseCrudComponent;
use App\Models\Rutina;
use App\Models\User;
use App\Services\RutinaService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;

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
            $query->where('nombre', 'like', '%'.$this->search.'%');
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

    protected RutinaService $rutinaService;

    public function mount(RutinaService $rutinaService)
    {
        $this->rutinaService = $rutinaService;
        
        // Cargar listas usando el servicio
        $this->atletas_list = $this->rutinaService->getAvailableAthletes(auth()->user());
    }

    public function toggleActive($rutinaId)
    {
        $rutina = Rutina::find($rutinaId);

        if (! $rutina) {
            return;
        }

        $isActive = $this->rutinaService->toggleActive($rutina);
        
        $message = $isActive ? 'Rutina activada correctamente.' : 'Rutina desactivada.';
        $this->dispatch('notify', message: $message);
    }
}
