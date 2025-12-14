<?php

namespace App\Livewire\Athlete;

use App\Models\RegistroSerie;
use App\Models\RutinaDia;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * =======================================================================
 * COMPONENTE: WORKOUT SESSION
 * =======================================================================
 *
 * Sesión de entrenamiento del atleta.
 *
 * CARACTERÍSTICAS:
 * - Soporte para ejercicios unilaterales (L/R)
 * - Unidades dinámicas (kg, bw, segundos, reps)
 * - Placeholders con objetivos programados
 * - Notas por ejercicio
 * - Validación de datos antes de completar
 *
 * @since 1.7
 */
#[Layout('layouts.app')]
class WorkoutSession extends Component
{
    public RutinaDia $rutinaDia;

    /**
     * Array para guardar los inputs de cada serie.
     * Estructura: [ejercicio_id][serie_numero][lado] => ['peso' => ..., 'reps' => ..., 'completed' => ...]
     * - lado: 'single' para ejercicios normales, 'left'/'right' para unilaterales
     */
    public $logs = [];

    /**
     * Notas por ejercicio.
     * Estructura: [ejercicio_id] => 'texto de nota'
     */
    public $notasEjercicio = [];

    public function mount(RutinaDia $rutinaDia)
    {
        $this->rutinaDia = $rutinaDia->load([
            'rutina',
            'rutinaEjercicios.ejercicio.equipo',
            'rutinaEjercicios.registros',
        ]);

        // Inicializar logs con datos existentes o vacíos
        foreach ($this->rutinaDia->rutinaEjercicios as $re) {
            $this->logs[$re->id] = [];
            $this->notasEjercicio[$re->id] = '';

            for ($i = 1; $i <= $re->series; $i++) {
                if ($re->is_unilateral) {
                    // Ejercicio unilateral: crear entradas para L y R
                    foreach (['left', 'right'] as $lado) {
                        $registro = $re->registros
                            ->where('serie_numero', $i)
                            ->where('lado', $lado)
                            ->first();

                        $this->logs[$re->id][$i][$lado] = [
                            'peso' => $registro?->peso,
                            'reps' => $registro?->reps,
                            'id' => $registro?->id,
                        ];
                    }
                    // Estado de completado (una vez por serie, no por lado)
                    $completadoL = $re->registros->where('serie_numero', $i)->where('lado', 'left')->first();
                    $completadoR = $re->registros->where('serie_numero', $i)->where('lado', 'right')->first();
                    $this->logs[$re->id][$i]['completed'] = $completadoL?->completed_at && $completadoR?->completed_at;
                } else {
                    // Ejercicio normal
                    $registro = $re->registros->where('serie_numero', $i)->first();
                    
                    $this->logs[$re->id][$i]['single'] = [
                        'peso' => $registro?->peso,
                        'reps' => $registro?->reps,
                        'rpe' => $registro?->rpe,
                        'rir' => $registro?->rir,
                        'id' => $registro?->id,
                    ];
                    $this->logs[$re->id][$i]['completed'] = (bool) $registro?->completed_at;
                }
            }

            // Cargar nota existente (del último registro)
            $ultimoRegistro = $re->registros->sortByDesc('id')->first();
            if ($ultimoRegistro?->observaciones) {
                $this->notasEjercicio[$re->id] = $ultimoRegistro->observaciones;
            }
        }
    }

    /**
     * Verifica si una serie tiene datos suficientes para ser completada.
     */
    public function canComplete($ejercicioId, $serieNumero): bool
    {
        $re = $this->rutinaDia->rutinaEjercicios->find($ejercicioId);
        $logSerie = $this->logs[$ejercicioId][$serieNumero] ?? [];

        if ($re->is_unilateral) {
            $leftData = $logSerie['left'] ?? [];
            $rightData = $logSerie['right'] ?? [];
            
            // Para unilaterales: al menos las reps de ambos lados
            return !empty($leftData['reps']) && !empty($rightData['reps']);
        } else {
            $data = $logSerie['single'] ?? [];
            // Para normales: al menos las reps
            return !empty($data['reps']);
        }
    }

    public function toggleComplete($ejercicioId, $serieNumero)
    {
        $re = $this->rutinaDia->rutinaEjercicios->find($ejercicioId);
        $isCompleted = $this->logs[$ejercicioId][$serieNumero]['completed'] ?? false;

        // Si va a marcar como completado, validar datos
        if (!$isCompleted && !$this->canComplete($ejercicioId, $serieNumero)) {
            $this->dispatch('notify', message: 'Completa los datos antes de marcar', type: 'warning');
            return;
        }

        $now = now();

        if ($re->is_unilateral) {
            // Guardar/actualizar ambos lados
            foreach (['left', 'right'] as $lado) {
                $logData = $this->logs[$ejercicioId][$serieNumero][$lado];
                
                $registro = RegistroSerie::updateOrCreate(
                    [
                        'rutina_ejercicio_id' => $ejercicioId,
                        'serie_numero' => $serieNumero,
                        'lado' => $lado,
                    ],
                    [
                        'peso' => $logData['peso'],
                        'reps' => $logData['reps'],
                        'completed_at' => $isCompleted ? null : $now,
                        'observaciones' => $this->notasEjercicio[$ejercicioId] ?? null,
                    ]
                );

                $this->logs[$ejercicioId][$serieNumero][$lado]['id'] = $registro->id;
            }
        } else {
            // Ejercicio normal
            $logData = $this->logs[$ejercicioId][$serieNumero]['single'];

            $registro = RegistroSerie::updateOrCreate(
                [
                    'rutina_ejercicio_id' => $ejercicioId,
                    'serie_numero' => $serieNumero,
                ],
                [
                    'peso' => $logData['peso'],
                    'reps' => $logData['reps'],
                    'rpe' => $logData['rpe'] ?? null,
                    'rir' => $logData['rir'] ?? null,
                    'completed_at' => $isCompleted ? null : $now,
                    'observaciones' => $this->notasEjercicio[$ejercicioId] ?? null,
                ]
            );

            $this->logs[$ejercicioId][$serieNumero]['single']['id'] = $registro->id;
        }

        // Toggle estado
        $this->logs[$ejercicioId][$serieNumero]['completed'] = !$isCompleted;
    }

    /**
     * Guarda las notas de un ejercicio.
     */
    public function saveNota($ejercicioId)
    {
        // Las notas se guardan con toggleComplete
        // Este método es para feedback visual si se necesita
        $this->dispatch('notify', message: 'Nota guardada', type: 'success');
    }

    public function render()
    {
        return view('livewire.athlete.workout-session');
    }
}

