<?php

namespace App\Livewire\Admin;

use App\Models\Rutina;
use App\Models\RutinaDia;
use App\Models\PlantillaDia;
use App\Models\RutinaEjercicio;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;

/**
 * Componente para gestionar el Calendario/Semana de una Rutina.
 * 
 * Permite:
 * 1. Visualizar los días de la rutina.
 * 2. Asignar plantillas a días específicos.
 * 3. Editar los ejercicios de un día (redirigir o modal).
 */
#[Layout('layouts.app')]
class GestionarRutinaCalendario extends Component
{
    public Rutina $rutina;
    public $dias = []; // Array de días cargados
    
    // Para el modal de asignación
    public $showAssignModal = false;
    public $selectedDiaId = null; // ID del RutinaDia seleccionado
    public $selectedPlantillaId = ''; // ID de la plantilla a copiar

    public function mount($id)
    {
        $this->rutina = Rutina::with('atleta')->findOrFail($id);
        $this->authorize('view', $this->rutina);
        
        $this->loadDias();
    }

    public function loadDias()
    {
        $this->dias = $this->rutina->dias()->orderBy('numero_dia')->get();
    }

    public function addDia()
    {
        $nuevoNumero = $this->rutina->dias()->max('numero_dia') + 1;
        
        RutinaDia::create([
            'rutina_id' => $this->rutina->id,
            'numero_dia' => $nuevoNumero,
            'nombre_dia' => 'Día ' . $nuevoNumero,
        ]);

        $this->loadDias();
        $this->dispatch('notify', message: 'Día agregado correctamente', type: 'success');
    }

    public function deleteDia($diaId)
    {
        $dia = RutinaDia::findOrFail($diaId);
        $dia->delete();

        // Reordenar días restantes (opcional, pero recomendado)
        $this->reorderDias();

        $this->loadDias();
        $this->dispatch('notify', message: 'Día eliminado correctamente', type: 'success');
    }

    public function updateDiaNombre($diaId, $nuevoNombre)
    {
        $dia = RutinaDia::findOrFail($diaId);
        $dia->update(['nombre_dia' => $nuevoNombre]);
        $this->dispatch('notify', message: 'Nombre actualizado', type: 'success');
    }

    private function reorderDias()
    {
        $dias = $this->rutina->dias()->orderBy('numero_dia')->get();
        foreach ($dias as $index => $dia) {
            $dia->update(['numero_dia' => $index + 1]);
        }
    }

    #[Computed]
    public function plantillas()
    {
        // Cargar plantillas del entrenador del atleta
        // Si el usuario autenticado es el entrenador, son sus plantillas.
        // Si es admin, podría ver todas o las del entrenador del atleta.
        
        $entrenadorId = $this->rutina->atleta->entrenador_id ?? auth()->id();
        
        return PlantillaDia::where('usuario_id', $entrenadorId)->get();
    }

    public function openAssignModal($diaId)
    {
        $this->selectedDiaId = $diaId;
        $this->selectedPlantillaId = '';
        $this->showAssignModal = true;
    }

    public function assignPlantilla()
    {
        $this->validate([
            'selectedPlantillaId' => 'required|exists:plantillas_dias,id',
        ]);

        $dia = RutinaDia::findOrFail($this->selectedDiaId);
        $plantilla = PlantillaDia::with('ejercicios')->findOrFail($this->selectedPlantillaId);

        // 1. Vincular plantilla
        $dia->update([
            'plantilla_dia_id' => $plantilla->id,
            // 'nombre_dia' => $plantilla->nombre // Opcional: cambiar nombre del día
        ]);

        // 2. Copiar ejercicios
        // Primero limpiar ejercicios existentes del día
        $dia->rutinaEjercicios()->delete();

        foreach ($plantilla->ejercicios as $ejercicioPlantilla) {
            RutinaEjercicio::create([
                'rutina_dia_id' => $dia->id,
                'ejercicio_id' => $ejercicioPlantilla->ejercicio_id,
                'series' => $ejercicioPlantilla->series,
                'repeticiones' => $ejercicioPlantilla->repeticiones,
                'peso_sugerido' => $ejercicioPlantilla->peso_sugerido,
                'descanso_segundos' => $ejercicioPlantilla->descanso_segundos,
                'indicaciones' => $ejercicioPlantilla->indicaciones,
                'orden' => $ejercicioPlantilla->orden,
            ]);
        }

        $this->showAssignModal = false;
        $this->loadDias(); // Recargar
        $this->dispatch('notify', message: 'Plantilla asignada correctamente', type: 'success');
    }

    public function clearDia($diaId)
    {
        $dia = RutinaDia::findOrFail($diaId);
        $dia->rutinaEjercicios()->delete();
        $dia->update(['plantilla_dia_id' => null]);
        
        $this->loadDias();
        $this->dispatch('notify', message: 'Día limpiado correctamente', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.gestionar-rutina-calendario');
    }
}
