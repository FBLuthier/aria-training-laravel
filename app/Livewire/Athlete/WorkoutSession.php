<?php

namespace App\Livewire\Athlete;

use Livewire\Component;
use App\Models\RutinaDia;

class WorkoutSession extends Component
{
    public RutinaDia $rutinaDia;
    public $ejercicios = [];
    public $logs = []; // Array para guardar los inputs: [ejercicio_id][serie_numero] => ['peso' => ..., 'reps' => ..., 'completed' => ...]

    public function mount(RutinaDia $rutinaDia)
    {
        $this->rutinaDia = $rutinaDia->load([
            'rutinaEjercicios.ejercicio', 
            'rutinaEjercicios.registros' // Cargar registros previos si existen
        ]);

        // Inicializar logs con datos existentes o vacíos
        foreach ($this->rutinaDia->rutinaEjercicios as $re) {
            $this->logs[$re->id] = [];
            for ($i = 1; $i <= $re->series; $i++) {
                // Buscar si ya existe registro
                $registro = $re->registros->where('serie_numero', $i)->first();
                
                $this->logs[$re->id][$i] = [
                    'peso' => $registro ? $registro->peso : null,
                    'reps' => $registro ? $registro->reps : null,
                    'rpe' => $registro ? $registro->rpe : null,
                    'rir' => $registro ? $registro->rir : null,
                    'completed' => $registro ? (bool)$registro->completed_at : false,
                    'id' => $registro ? $registro->id : null,
                ];
            }
        }
    }

    public function toggleComplete($ejercicioId, $serieNumero)
    {
        $logData = $this->logs[$ejercicioId][$serieNumero];
        
        // Validar que haya datos antes de marcar como completo
        if (empty($logData['peso']) && empty($logData['reps'])) {
            // Opcional: Mostrar error o toast
            return;
        }

        $now = now();
        
        // Guardar o Actualizar
        $registro = \App\Models\RegistroSerie::updateOrCreate(
            [
                'rutina_ejercicio_id' => $ejercicioId,
                'serie_numero' => $serieNumero,
            ],
            [
                'peso' => $logData['peso'],
                'reps' => $logData['reps'],
                'rpe' => $logData['rpe'] ?? null,
                'rir' => $logData['rir'] ?? null,
                'completed_at' => $logData['completed'] ? null : $now, // Si estaba completed (true), lo pasamos a false (null). Si no, a now.
            ]
        );

        // Actualizar estado local
        $this->logs[$ejercicioId][$serieNumero]['completed'] = !$logData['completed'];
        $this->logs[$ejercicioId][$serieNumero]['id'] = $registro->id;
    }

    public function render()
    {
        return view('livewire.athlete.workout-session');
    }
}
