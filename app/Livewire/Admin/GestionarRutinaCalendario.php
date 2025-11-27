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
        $this->rutina = Rutina::with('usuario')->findOrFail($id);
        $this->authorize('view', $this->rutina);
        
        $this->loadDias();
    }

    public function loadDias()
    {
        // Cargar días ordenados. Si no existen, podríamos crearlos (ej: 7 días por defecto)
        // Por ahora asumimos que se crean dinámicamente o mostramos los existentes.
        // Si la rutina es nueva, quizás queramos inicializarla con 7 días vacíos.
        
        $this->dias = $this->rutina->dias()->orderBy('numero_dia')->get();

        if ($this->dias->isEmpty()) {
            $this->initializeWeek();
        }
    }

    public function initializeWeek()
    {
        // Crear 7 días base para la semana
        $nombres = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        
        foreach ($nombres as $index => $nombre) {
            RutinaDia::create([
                'rutina_id' => $this->rutina->id,
                'numero_dia' => $index + 1,
                'nombre_dia' => $nombre,
            ]);
        }
        
        $this->dias = $this->rutina->dias()->orderBy('numero_dia')->get();
    }

    #[Computed]
    public function plantillas()
    {
        // Cargar plantillas del atleta (dueño de la rutina)
        return PlantillaDia::where('usuario_id', $this->rutina->usuario_id)->get();
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
