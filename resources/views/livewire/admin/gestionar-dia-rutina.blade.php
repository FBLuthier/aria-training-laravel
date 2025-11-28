<div x-data="{ showCreateModal: @entangle('showCreateEjercicioModal') }">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-4">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Editando:
                </h2>
                <input type="text" 
                       value="{{ $dia->nombre_dia }}" 
                       wire:change="updateNombreDia($event.target.value)"
                       class="font-bold text-lg text-gray-800 dark:text-gray-200 bg-transparent border-none focus:ring-0 p-0 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 rounded px-2 transition-colors"
                />
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.rutinas.calendario', $dia->rutina_id) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                    &larr; Volver al Calendario
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-6">
            
            {{-- Columna Izquierda: Lista de Ejercicios (La Rutina) --}}
            <div class="w-full lg:w-2/3 space-y-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Ejercicios del Día</h3>
                    
                    @if($dia->rutinaEjercicios->isEmpty())
                        <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                            <p>No hay ejercicios asignados a este día.</p>
                            <p class="text-sm">Usa el buscador de la derecha para añadir ejercicios.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($dia->rutinaEjercicios as $re)
                                <div wire:key="re-{{ $re->id }}" class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-700/50">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h4 class="font-bold text-gray-800 dark:text-gray-200">{{ $re->ejercicio->nombre }}</h4>
                                            <span class="text-xs text-gray-500">{{ $re->ejercicio->grupoMuscular->nombre ?? 'General' }}</span>
                                        </div>
                                        <button wire:click="removeEjercicio({{ $re->id }})" class="text-red-500 hover:text-red-700 text-sm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>

                                    {{-- Grid de Edición --}}
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Series</label>
                                            <input type="number" 
                                                   wire:model.blur="ejerciciosData.{{ $re->id }}.series"
                                                   wire:change="updateEjercicio({{ $re->id }}, 'series', $event.target.value)"
                                                   class="mt-1 block w-full text-sm rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Repeticiones</label>
                                            <input type="text" 
                                                   wire:model.blur="ejerciciosData.{{ $re->id }}.repeticiones"
                                                   wire:change="updateEjercicio({{ $re->id }}, 'repeticiones', $event.target.value)"
                                                   class="mt-1 block w-full text-sm rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Peso</label>
                                            <div class="relative mt-1 rounded-md shadow-sm">
                                                <input type="text" 
                                                       wire:model.blur="ejerciciosData.{{ $re->id }}.peso_sugerido"
                                                       wire:change="updateEjercicio({{ $re->id }}, 'peso_sugerido', $event.target.value)"
                                                       class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm pr-16"
                                                >
                                                <div class="absolute inset-y-0 right-0 flex items-center">
                                                    <select wire:model.blur="ejerciciosData.{{ $re->id }}.unidad_peso"
                                                            wire:change="updateEjercicio({{ $re->id }}, 'unidad_peso', $event.target.value)"
                                                            class="h-full rounded-md border-0 bg-transparent py-0 pl-2 pr-7 text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-xs"
                                                    >
                                                        <option value="kg">kg</option>
                                                        <option value="lbs">lbs</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Descanso (seg)</label>
                                            <input type="number" 
                                                   wire:model.blur="ejerciciosData.{{ $re->id }}.descanso_segundos"
                                                   wire:change="updateEjercicio({{ $re->id }}, 'descanso_segundos', $event.target.value)"
                                                   class="mt-1 block w-full text-sm rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                            >
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Indicaciones / Notas</label>
                                        <input type="text" 
                                               wire:model.blur="ejerciciosData.{{ $re->id }}.indicaciones"
                                               wire:change="updateEjercicio({{ $re->id }}, 'indicaciones', $event.target.value)"
                                               class="mt-1 block w-full text-sm rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                               placeholder="Ej: Controlar la excéntrica..."
                                        >
                                    </div>

                                    {{-- TEMPO CONFIG --}}
                                    <div class="mt-4 border-t border-gray-100 dark:border-gray-600 pt-3">
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" 
                                                   id="has_tempo_{{ $re->id }}" 
                                                   wire:model.live="ejerciciosData.{{ $re->id }}.has_tempo"
                                                   wire:change="updateEjercicio({{ $re->id }}, 'has_tempo', $event.target.checked)"
                                                   class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:bg-gray-900"
                                            >
                                            <label for="has_tempo_{{ $re->id }}" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Habilitar Tempo</label>
                                        </div>

                                        @if(!empty($ejerciciosData[$re->id]['has_tempo']))
                                            <div class="grid grid-cols-3 gap-4 bg-gray-100 dark:bg-gray-800 p-3 rounded-md">
                                                
                                                {{-- FASE 1: BAJAR --}}
                                                <div class="flex flex-col gap-2">
                                                    <select wire:model.blur="ejerciciosData.{{ $re->id }}.tempo.fase1.accion"
                                                            wire:change="updateEjercicio({{ $re->id }}, 'tempo.fase1.accion', $event.target.value)"
                                                            class="text-xs rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                                    >
                                                        <option value="Bajar">Bajar</option>
                                                        <option value="Subir">Subir</option>
                                                        <option value="Tomar aire">Tomar aire</option>
                                                    </select>
                                                    <div class="flex items-center gap-1">
                                                        <input type="number" 
                                                               wire:model.blur="ejerciciosData.{{ $re->id }}.tempo.fase1.tiempo"
                                                               wire:change="updateEjercicio({{ $re->id }}, 'tempo.fase1.tiempo', $event.target.value)"
                                                               class="w-full text-xs rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                                               placeholder="Seg"
                                                        >
                                                        <span class="text-xs text-gray-500">s</span>
                                                    </div>
                                                </div>

                                                {{-- FASE 2: MANTENER --}}
                                                <div class="flex flex-col gap-2 text-center">
                                                    <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 py-2">Mantener</span>
                                                    <div class="flex items-center gap-1">
                                                        <input type="number" 
                                                               wire:model.blur="ejerciciosData.{{ $re->id }}.tempo.fase2.tiempo"
                                                               wire:change="updateEjercicio({{ $re->id }}, 'tempo.fase2.tiempo', $event.target.value)"
                                                               class="w-full text-xs rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                                               placeholder="Seg"
                                                        >
                                                        <span class="text-xs text-gray-500">s</span>
                                                    </div>
                                                </div>

                                                {{-- FASE 3: SUBIR --}}
                                                <div class="flex flex-col gap-2">
                                                    <select wire:model.blur="ejerciciosData.{{ $re->id }}.tempo.fase3.accion"
                                                            wire:change="updateEjercicio({{ $re->id }}, 'tempo.fase3.accion', $event.target.value)"
                                                            class="text-xs rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                                    >
                                                        <option value="Subir">Subir</option>
                                                        <option value="Bajar">Bajar</option>
                                                        <option value="Soltar aire">Soltar aire</option>
                                                    </select>
                                                    <div class="flex items-center gap-1">
                                                        <input type="number" 
                                                               wire:model.blur="ejerciciosData.{{ $re->id }}.tempo.fase3.tiempo"
                                                               wire:change="updateEjercicio({{ $re->id }}, 'tempo.fase3.tiempo', $event.target.value)"
                                                               class="w-full text-xs rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                                               placeholder="Seg"
                                                        >
                                                        <span class="text-xs text-gray-500">s</span>
                                                    </div>
                                                </div>

                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Columna Derecha: Buscador --}}
            <div class="w-full lg:w-1/3">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 sticky top-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Añadir Ejercicio</h3>
                        <button @click="showCreateModal = true" class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white py-1 px-2 rounded transition-colors">
                            + Crear Nuevo
                        </button>
                    </div>
                    
                    <div class="relative">
                        <input type="text" 
                               wire:model.live.debounce.300ms="search"
                               placeholder="Buscar ejercicio..." 
                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >
                        
                        @if(strlen($search) >= 2)
                            <div class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-700 rounded-md shadow-lg border border-gray-200 dark:border-gray-600 max-h-60 overflow-y-auto">
                                @forelse($this->searchResults as $ejercicio)
                                    <button wire:click="addEjercicio({{ $ejercicio->id }})" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors border-b border-gray-100 dark:border-gray-600 last:border-0">
                                        <div class="font-medium text-gray-800 dark:text-gray-200">{{ $ejercicio->nombre }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $ejercicio->grupoMuscular->nombre ?? 'General' }}</div>
                                    </button>
                                @empty
                                    <div class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">No se encontraron resultados.</div>
                                @endforelse
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 text-xs text-gray-500 dark:text-gray-400">
                        <p>Busca por nombre o grupo muscular.</p>
                        <p class="mt-2">Los ejercicios se añadirán al final de la lista del día.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal Crear Ejercicio (Alpine Control) --}}
    <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showCreateModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Crear Nuevo Ejercicio
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <x-input-label for="newEjercicioNombre" value="Nombre del Ejercicio" />
                                    <x-text-input wire:model="newEjercicioNombre" id="newEjercicioNombre" class="block w-full mt-1" placeholder="Ej: Press Militar con Mancuernas" />
                                    @error('newEjercicioNombre') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <x-input-label for="newEjercicioGrupoMuscularId" value="Grupo Muscular" />
                                    <select wire:model="newEjercicioGrupoMuscularId" id="newEjercicioGrupoMuscularId" class="block w-full mt-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                        <option value="">Seleccionar...</option>
                                        @foreach($gruposMusculares as $grupo)
                                            <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('newEjercicioGrupoMuscularId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="createEjercicio" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Crear Ejercicio
                    </button>
                    <button type="button" @click="showCreateModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
