<div class="min-h-screen bg-gray-50 dark:bg-black pb-20">
    {{-- Header Fijo --}}
    <div class="sticky top-0 z-20 bg-white dark:bg-zinc-900 border-b border-gray-200 dark:border-zinc-800 px-4 py-4 shadow-sm">
        <div class="flex justify-between items-center max-w-lg mx-auto">
            <a href="{{ route('dashboard') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div class="text-center">
                <h1 class="text-lg font-bold text-gray-900 dark:text-white leading-tight">
                    {{ $rutinaDia->nombre_dia }}
                </h1>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $rutinaDia->rutina->nombre }}
                </p>
            </div>
            <div class="w-6"></div>
        </div>
    </div>

    <div class="max-w-lg mx-auto px-4 py-6 space-y-6">
        
        {{-- Lista de Ejercicios --}}
        @foreach($rutinaDia->rutinaEjercicios as $re)
            @php
                // Determinar unidades dinámicas
                $unidadPeso = $re->unidad_peso ?? 'kg';
                $unidadReps = $re->unidad_repeticiones ?? 'reps';
                
                // Labels para headers
                $labelPeso = match($unidadPeso) {
                    'bw' => 'BW',
                    'banda' => 'Banda',
                    'kg' => 'KG',
                    'lb' => 'LB',
                    default => strtoupper($unidadPeso)
                };
                
                $labelReps = match($unidadReps) {
                    'segundos' => 'SEG',
                    'respiraciones' => 'RESP',
                    'reps' => 'REPS',
                    default => strtoupper($unidadReps)
                };
                
                // Equipo del ejercicio
                $equipoNombre = $re->ejercicio->equipo?->nombre ?? 'Peso Corporal';
            @endphp

            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800 overflow-hidden">
                
                {{-- Cabecera Ejercicio --}}
                <div class="p-4 border-b border-gray-100 dark:border-zinc-800 bg-gray-50/50 dark:bg-zinc-800/50">
                    <div class="flex justify-between items-start">
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">
                            {{ $re->ejercicio->nombre }} 
                            <span class="font-normal text-gray-500 dark:text-gray-400">({{ $equipoNombre }})</span>
                        </h3>
                    </div>
                    
                    @if($re->indicaciones)
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 bg-yellow-50 dark:bg-yellow-900/20 p-2 rounded border border-yellow-100 dark:border-yellow-900/30">
                            <span class="font-bold text-yellow-600 dark:text-yellow-500">Nota:</span> {{ $re->indicaciones }}
                        </p>
                    @endif
                    
                    <div class="mt-2 flex gap-3 text-xs text-gray-500 dark:text-gray-400">
                        @if($re->tempo)
                            <span class="flex items-center gap-1 bg-gray-100 dark:bg-zinc-700 px-2 py-1 rounded">
                                ⏱ Tempo: {{ $re->tempo['fase1']['tiempo'] ?? 0 }}-{{ $re->tempo['fase2']['tiempo'] ?? 0 }}-{{ $re->tempo['fase3']['tiempo'] ?? 0 }}
                            </span>
                        @endif
                        @if($re->is_unilateral)
                            <span class="flex items-center gap-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-2 py-1 rounded">
                                ↔️ Unilateral
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Tabla de Series --}}
                <div class="p-4">
                    {{-- Headers dinámicos --}}
                    <div class="grid gap-2 mb-2 text-[10px] font-bold text-gray-400 uppercase tracking-wider text-center
                                {{ $re->is_unilateral ? 'grid-cols-10' : 'grid-cols-9' }}">
                        <div class="col-span-1">#</div>
                        @if($re->is_unilateral)
                            <div class="col-span-1">Lado</div>
                        @endif
                        <div class="col-span-3">{{ $labelPeso }}</div>
                        <div class="col-span-3">{{ $labelReps }}</div>
                        <div class="col-span-2">✓</div>
                    </div>

                    @for($i = 1; $i <= $re->series; $i++)
                        @if($re->is_unilateral)
                            {{-- EJERCICIO UNILATERAL: Dos filas por serie --}}
                            @foreach(['left' => 'L', 'right' => 'R'] as $lado => $labelLado)
                                <div class="grid grid-cols-10 gap-2 items-center {{ $lado === 'left' ? 'mb-1' : 'mb-3' }} last:mb-0" 
                                     wire:key="row-{{ $re->id }}-{{ $i }}-{{ $lado }}">
                                    
                                    {{-- Número Serie (solo en L) --}}
                                    <div class="col-span-1 flex justify-center">
                                        @if($lado === 'left')
                                            <span class="w-6 h-6 rounded-full bg-gray-100 dark:bg-zinc-800 text-gray-500 dark:text-gray-400 text-xs flex items-center justify-center font-bold">
                                                {{ $i }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Label Lado --}}
                                    <div class="col-span-1 flex justify-center">
                                        <span class="text-xs font-bold {{ $lado === 'left' ? 'text-blue-500' : 'text-green-500' }}">
                                            {{ $labelLado }}
                                        </span>
                                    </div>

                                    {{-- Input Peso --}}
                                    <div class="col-span-3">
                                        <input type="text" 
                                               wire:model.blur="logs.{{ $re->id }}.{{ $i }}.{{ $lado }}.peso"
                                               placeholder="{{ $unidadPeso !== 'bw' ? ($re->peso_sugerido ?? '') : '' }}"
                                               class="w-full text-center py-1.5 px-1 text-sm rounded bg-gray-50 dark:bg-zinc-800 border-gray-200 dark:border-zinc-700 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none {{ $unidadPeso === 'bw' ? 'bg-gray-200 dark:bg-zinc-700 cursor-not-allowed' : '' }}"
                                               {{ ($logs[$re->id][$i]['completed'] ?? false) || $unidadPeso === 'bw' ? 'disabled' : '' }}
                                        >
                                    </div>

                                    {{-- Input Reps --}}
                                    <div class="col-span-3">
                                        <input type="text" 
                                               wire:model.blur="logs.{{ $re->id }}.{{ $i }}.{{ $lado }}.reps"
                                               placeholder="{{ $re->repeticiones ?? '' }}"
                                               class="w-full text-center py-1.5 px-1 text-sm rounded bg-gray-50 dark:bg-zinc-800 border-gray-200 dark:border-zinc-700 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                               {{ $logs[$re->id][$i]['completed'] ?? false ? 'disabled' : '' }}
                                        >
                                    </div>

                                    {{-- Checkbox Completado (solo en R) --}}
                                    <div class="col-span-2 flex justify-center">
                                        @if($lado === 'right')
                                            <button type="button"
                                                    wire:click="toggleComplete({{ $re->id }}, {{ $i }})"
                                                    class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200 
                                                           {{ ($logs[$re->id][$i]['completed'] ?? false)
                                                               ? 'bg-green-500 text-white shadow-lg shadow-green-500/30 scale-105' 
                                                               : 'bg-gray-100 dark:bg-zinc-800 text-gray-300 dark:text-gray-600 hover:bg-gray-200 dark:hover:bg-zinc-700' }}"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            {{-- EJERCICIO NORMAL: Una fila por serie --}}
                            <div class="grid grid-cols-9 gap-2 items-center mb-3 last:mb-0" wire:key="row-{{ $re->id }}-{{ $i }}">
                                {{-- Número Serie --}}
                                <div class="col-span-1 flex justify-center">
                                    <span class="w-6 h-6 rounded-full bg-gray-100 dark:bg-zinc-800 text-gray-500 dark:text-gray-400 text-xs flex items-center justify-center font-bold">
                                        {{ $i }}
                                    </span>
                                </div>

                                {{-- Input Peso --}}
                                <div class="col-span-3">
                                    <input type="text" 
                                           wire:model.blur="logs.{{ $re->id }}.{{ $i }}.single.peso"
                                           placeholder="{{ $unidadPeso !== 'bw' ? ($re->peso_sugerido ?? '') : '' }}"
                                           class="w-full text-center py-1.5 px-1 text-sm rounded bg-gray-50 dark:bg-zinc-800 border-gray-200 dark:border-zinc-700 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none {{ $unidadPeso === 'bw' ? 'bg-gray-200 dark:bg-zinc-700 cursor-not-allowed' : '' }}"
                                           {{ ($logs[$re->id][$i]['completed'] ?? false) || $unidadPeso === 'bw' ? 'disabled' : '' }}
                                    >
                                </div>

                                {{-- Input Reps --}}
                                <div class="col-span-3">
                                    <input type="text" 
                                           wire:model.blur="logs.{{ $re->id }}.{{ $i }}.single.reps"
                                           placeholder="{{ $re->repeticiones ?? '' }}"
                                           class="w-full text-center py-1.5 px-1 text-sm rounded bg-gray-50 dark:bg-zinc-800 border-gray-200 dark:border-zinc-700 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                           {{ $logs[$re->id][$i]['completed'] ?? false ? 'disabled' : '' }}
                                    >
                                </div>

                                {{-- Checkbox Completado --}}
                                <div class="col-span-2 flex justify-center">
                                    <button type="button"
                                            wire:click="toggleComplete({{ $re->id }}, {{ $i }})"
                                            class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200 
                                                   {{ ($logs[$re->id][$i]['completed'] ?? false)
                                                       ? 'bg-green-500 text-white shadow-lg shadow-green-500/30 scale-105' 
                                                       : 'bg-gray-100 dark:bg-zinc-800 text-gray-300 dark:text-gray-600 hover:bg-gray-200 dark:hover:bg-zinc-700' }}"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                </div>
                            </div>
                            
                            {{-- RPE / RIR Inputs (Si habilitado) --}}
                            @if(($re->track_rpe || $re->track_rir) && !($logs[$re->id][$i]['completed'] ?? false))
                                <div class="grid grid-cols-9 gap-2 mb-3 -mt-1">
                                    <div class="col-span-1"></div>
                                    <div class="col-span-6 flex gap-2 justify-end">
                                        @if($re->track_rpe)
                                            <div class="flex items-center gap-1">
                                                <span class="text-[10px] text-gray-400">RPE</span>
                                                <input type="number" step="0.5" wire:model.blur="logs.{{ $re->id }}.{{ $i }}.single.rpe" class="w-12 py-0.5 text-xs text-center rounded border-gray-200 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                                            </div>
                                        @endif
                                        @if($re->track_rir)
                                            <div class="flex items-center gap-1">
                                                <span class="text-[10px] text-gray-400">RIR</span>
                                                <input type="number" wire:model.blur="logs.{{ $re->id }}.{{ $i }}.single.rir" class="w-12 py-0.5 text-xs text-center rounded border-gray-200 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-span-2"></div>
                                </div>
                            @endif
                        @endif
                    @endfor
                </div>

                {{-- Notas del Ejercicio --}}
                <div class="p-4 border-t border-gray-100 dark:border-zinc-800 bg-gray-50/30 dark:bg-zinc-800/30">
                    <textarea wire:model.blur="notasEjercicio.{{ $re->id }}"
                              placeholder="Notas sobre este ejercicio (opcional)..."
                              class="w-full text-sm rounded-lg border-gray-200 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white resize-none placeholder-gray-400 dark:placeholder-gray-500"
                              rows="2"></textarea>
                    
                    {{-- Botón Video --}}
                    <button type="button" class="mt-2 text-xs text-indigo-500 hover:text-indigo-600 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        Agregar video
                    </button>
                </div>
            </div>
        @endforeach

        {{-- Botón Finalizar --}}
        <div class="pt-4">
            <button onclick="confirm('¿Terminar entrenamiento?') || event.stopImmediatePropagation()" wire:click="finishWorkout" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-indigo-500/30 transition-all active:scale-[0.98]">
                Terminar Entrenamiento
            </button>
        </div>

    </div>
</div>
