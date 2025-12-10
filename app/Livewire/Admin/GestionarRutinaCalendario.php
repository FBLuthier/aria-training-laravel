<?php

namespace App\Livewire\Admin;

use App\Models\Rutina;
use App\Models\RutinaDia;
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
    public $currentMonth;
    public $currentYear;

    public $rutinasAtleta;
    public $atletas_list;

    public function mount($id)
    {
        $this->rutina = Rutina::with('atleta')->findOrFail($id);
        $this->authorize('view', $this->rutina);
        
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;

        // Cargar todas las rutinas del atleta para el selector
        if ($this->rutina->atleta_id) {
            $this->rutinasAtleta = Rutina::where('atleta_id', $this->rutina->atleta_id)
                ->select('id', 'nombre', 'estado')
                ->get();
        } else {
            $this->rutinasAtleta = collect();
        }

        // Cargar lista de atletas para navegación rápida
        if (auth()->user()->esEntrenador()) {
            $this->atletas_list = \App\Models\User::where('entrenador_id', auth()->id())->get();
        } else {
            $this->atletas_list = \App\Models\User::where('tipo_usuario_id', 3)->get();
        }
    }

    public function switchAthlete($athleteId)
    {
        if (!$athleteId) return;

        // Buscar rutina activa del atleta seleccionado
        $activeRoutine = Rutina::where('atleta_id', $athleteId)
            ->where('estado', 1)
            ->first();

        if ($activeRoutine) {
            return redirect()->route('admin.rutinas.calendario', $activeRoutine->id);
        }

        // Si no tiene activa, buscar la última modificada
        $lastRoutine = Rutina::where('atleta_id', $athleteId)
            ->latest('updated_at')
            ->first();

        if ($lastRoutine) {
            return redirect()->route('admin.rutinas.calendario', $lastRoutine->id);
        }

        // Si no tiene rutinas, volver al listado general
        // Idealmente pasaríamos el filtro, pero por ahora al listado base
        return redirect()->route('admin.rutinas');
    }

    #[Computed]
    public function dias()
    {
        return $this->rutina->dias()
            ->with(['rutinaEjercicios.ejercicio', 'bloques'])
            ->orderBy('numero_dia')
            ->get();
    }

    #[Computed]
    public function diasSinFecha()
    {
        return $this->dias()->whereNull('fecha');
    }

    #[Computed]
    public function diasProgramados()
    {
        return $this->dias()->whereNotNull('fecha')->groupBy(function($dia) {
            return $dia->fecha->format('Y-m-d');
        });
    }

    public function changeMonth($increment)
    {
        $date = \Carbon\Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->addMonths($increment);
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
    }

    public function assignFecha($diaId, $fecha)
    {
        $dia = RutinaDia::findOrFail($diaId);
        $dia->update(['fecha' => $fecha]);
        
        $this->dispatch('notify', message: 'Día programado correctamente', type: 'success');
    }

    public function removeFecha($diaId)
    {
        $dia = RutinaDia::findOrFail($diaId);
        $dia->update(['fecha' => null]);
        
        $this->dispatch('notify', message: 'Día movido al banco', type: 'success');
    }

    public function copyToNextWeek($diaId)
    {
        $dia = RutinaDia::findOrFail($diaId);
        if ($dia->fecha) {
            $nextWeek = $dia->fecha->copy()->addWeek()->format('Y-m-d');
            $this->duplicateDia($diaId, $nextWeek);
        }
    }

    public function addDia()
    {
        $nuevoNumero = $this->rutina->dias()->max('numero_dia') + 1;
        
        RutinaDia::create([
            'rutina_id' => $this->rutina->id,
            'numero_dia' => $nuevoNumero,
            'nombre_dia' => 'Día ' . $nuevoNumero,
            'fecha' => null, // Por defecto al banco
        ]);

        $this->dispatch('notify', message: 'Día agregado al banco', type: 'success');
    }

    public function duplicateDia($diaId, $targetDate = null)
    {
        $diaOriginal = RutinaDia::with(['bloques', 'rutinaEjercicios'])->findOrFail($diaId);
        
        $nuevoNumero = $this->rutina->dias()->max('numero_dia') + 1;
        
        // Generar nombre con sufijo
        $nombreBase = $diaOriginal->nombre_dia;
        // Detectar si ya tiene sufijo (N)
        if (preg_match('/\((\d+)\)$/', $nombreBase, $matches)) {
            $sufijo = intval($matches[1]) + 1;
            $nuevoNombre = preg_replace('/\((\d+)\)$/', "($sufijo)", $nombreBase);
        } else {
            $nuevoNombre = $nombreBase . " (1)";
        }

        // Crear nuevo día
        $nuevoDia = RutinaDia::create([
            'rutina_id' => $this->rutina->id,
            'numero_dia' => $nuevoNumero,
            'nombre_dia' => $nuevoNombre,
            'fecha' => $targetDate,
        ]);

        // Mapa de IDs de bloques antiguos a nuevos para reasignar ejercicios
        $bloqueMap = [];

        // 1. Clonar Bloques
        foreach ($diaOriginal->bloques as $bloque) {
            $nuevoBloque = $nuevoDia->bloques()->create([
                'nombre' => $bloque->nombre,
                'orden' => $bloque->orden,
            ]);
            $bloqueMap[$bloque->id] = $nuevoBloque->id;
        }

        // 2. Clonar Ejercicios
        foreach ($diaOriginal->rutinaEjercicios as $ejercicio) {
            $nuevoBloqueId = null;
            if ($ejercicio->rutina_bloque_id && isset($bloqueMap[$ejercicio->rutina_bloque_id])) {
                $nuevoBloqueId = $bloqueMap[$ejercicio->rutina_bloque_id];
            }

            $nuevoDia->rutinaEjercicios()->create([
                'rutina_bloque_id' => $nuevoBloqueId,
                'ejercicio_id' => $ejercicio->ejercicio_id,
                'series' => $ejercicio->series,
                'repeticiones' => $ejercicio->repeticiones,
                'peso_sugerido' => $ejercicio->peso_sugerido,
                'unidad_peso' => $ejercicio->unidad_peso,
                'descanso_segundos' => $ejercicio->descanso_segundos,
                'indicaciones' => $ejercicio->indicaciones,
                'orden_en_dia' => $ejercicio->orden_en_dia,
                'tempo' => $ejercicio->tempo,
            ]);
        }

        $this->dispatch('notify', message: 'Día duplicado correctamente', type: 'success');
    }

    public $deletingDiaId = null;
    public $confirmingDiaDeletion = false;

    public function confirmDeleteDia($diaId)
    {
        $this->deletingDiaId = $diaId;
        $this->confirmingDiaDeletion = true;
    }

    public function performDeleteDia()
    {
        $dia = RutinaDia::findOrFail($this->deletingDiaId);
        $dia->delete();

        // Reordenar días restantes (opcional, pero recomendado)
        $this->reorderDias();

        $this->deletingDiaId = null;
        $this->confirmingDiaDeletion = false;
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

    public function clearDia($diaId)
    {
        $dia = RutinaDia::findOrFail($diaId);
        $dia->rutinaEjercicios()->delete();
        $dia->bloques()->delete(); // También limpiar bloques
        // $dia->update(['plantilla_dia_id' => null]); // Ya no se usa
        
        $this->dispatch('notify', message: 'Día limpiado correctamente', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.gestionar-rutina-calendario');
    }
}
