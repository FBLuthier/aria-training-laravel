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
            <div class="w-6"></div> {{-- Espaciador para centrar --}}
        </div>
    </div>

    <div class="max-w-lg mx-auto px-4 py-6 space-y-6">
        
        {{-- Lista de Ejercicios --}}
        @foreach($rutinaDia->rutinaEjercicios as $re)
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800 overflow-hidden">
                
                {{-- Cabecera Ejercicio --}}
                <div class="p-4 border-b border-gray-100 dark:border-zinc-800 bg-gray-50/50 dark:bg-zinc-800/50">
                    <div class="flex justify-between items-start">
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">
                            {{ $re->ejercicio->nombre }}
                        </h3>
                        {{-- Icono Info / Video (Futuro) --}}
                    </div>
                    @if($re->indicaciones)
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 bg-yellow-50 dark:bg-yellow-900/20 p-2 rounded border border-yellow-100 dark:border-yellow-900/30">
                            <span class="font-bold text-yellow-600 dark:text-yellow-500">Nota:</span> {{ $re->indicaciones }}
                        </p>
                    @endif
                    <div class="mt-2 flex gap-3 text-xs text-gray-500 dark:text-gray-400">
                        @if($re->tempo)
                            <span class="flex items-center gap-1 bg-gray-100 dark:bg-zinc-700 px-2 py-1 rounded">
                                ⏱ Tempo: {{ $re->tempo['fase1']['tiempo'] }}-{{ $re->tempo['fase2']['tiempo'] }}-{{ $re->tempo['fase3']['tiempo'] }}
                            </span>
                        @endif
                        <span class="flex items-center gap-1 bg-gray-100 dark:bg-zinc-700 px-2 py-1 rounded">
                            ⚖️ Descanso: {{ $re->descanso ?? '90s' }}
                        </span>
                    </div>
                </div>

                {{-- Tabla de Series --}}
                <div class="p-4">
                    <div class="grid grid-cols-12 gap-2 mb-2 text-[10px] font-bold text-gray-400 uppercase tracking-wider text-center">
                        <div class="col-span-1">#</div>
                        <div class="col-span-3 text-left pl-2">Objetivo</div>
                        <div class="col-span-3">Kg</div>
                        <div class="col-span-3">Reps</div>
                        <div class="col-span-2">Done</div>
                    </div>

                    @for($i = 1; $i <= $re->series; $i++)
                        <div class="grid grid-cols-12 gap-2 items-center mb-3 last:mb-0" wire:key="row-{{ $re->id }}-{{ $i }}">
                            {{-- Número Serie --}}
                            <div class="col-span-1 flex justify-center">
                                <span class="w-6 h-6 rounded-full bg-gray-100 dark:bg-zinc-800 text-gray-500 dark:text-gray-400 text-xs flex items-center justify-center font-bold">
                                    {{ $i }}
                                </span>
                            </div>

                            {{-- Objetivo (Prescripción) --}}
                            <div class="col-span-3 text-xs text-gray-600 dark:text-gray-300 pl-2">
                                {{ $re->peso_sugerido ? $re->peso_sugerido . $re->unidad_peso : '-' }} 
                                <span class="text-gray-300 mx-1">x</span> 
                                {{ $re->repeticiones }}
                            </div>

                            {{-- Input Peso --}}
                            <div class="col-span-3">
                                <input type="number" 
                                       wire:model.blur="logs.{{ $re->id }}.{{ $i }}.peso"
                                       placeholder="-" 
                                       class="w-full text-center py-1.5 px-0 text-sm rounded bg-gray-50 dark:bg-zinc-800 border-gray-200 dark:border-zinc-700 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white placeholder-gray-300"
                                       {{ $logs[$re->id][$i]['completed'] ? 'disabled' : '' }}
                                >
                            </div>

                            {{-- Input Reps --}}
                            <div class="col-span-3">
                                <input type="number" 
                                       wire:model.blur="logs.{{ $re->id }}.{{ $i }}.reps"
                                       placeholder="-" 
                                       class="w-full text-center py-1.5 px-0 text-sm rounded bg-gray-50 dark:bg-zinc-800 border-gray-200 dark:border-zinc-700 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white placeholder-gray-300"
                                       {{ $logs[$re->id][$i]['completed'] ? 'disabled' : '' }}
                                >
                            </div>

                            {{-- Checkbox Completado --}}
                            <div class="col-span-2 flex justify-center">
                                <button wire:click="toggleComplete({{ $re->id }}, {{ $i }})"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200 {{ $logs[$re->id][$i]['completed'] ? 'bg-green-500 text-white shadow-lg shadow-green-500/30 scale-105' : 'bg-gray-100 dark:bg-zinc-800 text-gray-300 hover:bg-gray-200 dark:hover:bg-zinc-700' }}"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                            </div>
                        </div>
                        
                        {{-- RPE / RIR Inputs (Si habilitado) --}}
                        @if(($re->track_rpe || $re->track_rir) && !$logs[$re->id][$i]['completed'])
                            <div class="grid grid-cols-12 gap-2 mb-3 -mt-1">
                                <div class="col-span-1"></div>
                                <div class="col-span-11 flex gap-2 justify-end pr-14">
                                    @if($re->track_rpe)
                                        <div class="flex items-center gap-1">
                                            <span class="text-[10px] text-gray-400">RPE</span>
                                            <input type="number" step="0.5" wire:model.blur="logs.{{ $re->id }}.{{ $i }}.rpe" class="w-12 py-0.5 text-xs text-center rounded border-gray-200 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                                        </div>
                                    @endif
                                    @if($re->track_rir)
                                        <div class="flex items-center gap-1">
                                            <span class="text-[10px] text-gray-400">RIR</span>
                                            <input type="number" wire:model.blur="logs.{{ $re->id }}.{{ $i }}.rir" class="w-12 py-0.5 text-xs text-center rounded border-gray-200 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                    @endfor
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
