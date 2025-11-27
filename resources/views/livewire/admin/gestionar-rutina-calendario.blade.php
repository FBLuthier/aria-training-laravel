<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Calendario de Rutina: {{ $rutina->nombre }}
            </h2>
            <a href="{{ route('admin.rutinas') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                &larr; Volver a Rutinas
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Info Rutina --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p><strong>Atleta:</strong> {{ $rutina->usuario->nombre_1 }} {{ $rutina->usuario->apellido_1 }}</p>
                    <p><strong>Objetivo:</strong> {{ $rutina->objetivo->nombre ?? 'N/A' }}</p>
                    @if($rutina->descripcion)
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $rutina->descripcion }}</p>
                    @endif
                </div>
            </div>

            {{-- Grid de Días --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($dias as $dia)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 flex flex-col h-full">
                        {{-- Cabecera del Día --}}
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                            <h3 class="font-bold text-lg text-gray-800 dark:text-gray-200">{{ $dia->nombre_dia }}</h3>
                            <div class="flex gap-2">
                                @if($dia->plantilla_dia_id || $dia->rutinaEjercicios->count() > 0)
                                    <button wire:click="clearDia({{ $dia->id }})" class="text-red-500 hover:text-red-700 text-xs" title="Limpiar día">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                @endif
                                <button wire:click="openAssignModal({{ $dia->id }})" class="text-blue-500 hover:text-blue-700 text-xs" title="Asignar Plantilla">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Contenido del Día --}}
                        <div class="p-4 flex-grow">
                            @if($dia->rutinaEjercicios->count() > 0)
                                <ul class="space-y-3">
                                    @foreach($dia->rutinaEjercicios as $ejercicio)
                                        <li class="text-sm border-b border-gray-100 dark:border-gray-700 pb-2 last:border-0">
                                            <div class="font-medium text-gray-800 dark:text-gray-200">{{ $ejercicio->ejercicio->nombre }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $ejercicio->series }} x {{ $ejercicio->repeticiones }}
                                                @if($ejercicio->peso_sugerido) | {{ $ejercicio->peso_sugerido }} @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="h-full flex flex-col items-center justify-center text-gray-400 dark:text-gray-500 py-8">
                                    <svg class="w-8 h-8 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    <span class="text-xs text-center">Sin ejercicios asignados</span>
                                    <button wire:click="openAssignModal({{ $dia->id }})" class="mt-2 text-xs text-indigo-500 hover:underline">Asignar Plantilla</button>
                                </div>
                            @endif
                        </div>
                        
                        @if($dia->plantillaDia)
                            <div class="px-4 py-2 bg-indigo-50 dark:bg-indigo-900/20 text-xs text-indigo-700 dark:text-indigo-300 border-t border-indigo-100 dark:border-indigo-800">
                                Basado en: <strong>{{ $dia->plantillaDia->nombre }}</strong>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    {{-- Modal Asignar Plantilla --}}
    <x-modal name="assign-modal" :show="$showAssignModal" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Asignar Plantilla al Día
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Selecciona una plantilla del "Banco de Días" de este atleta para copiar sus ejercicios.
            </p>

            <div class="mt-6">
                <x-input-label for="plantilla" value="Seleccionar Plantilla" />
                <select wire:model="selectedPlantillaId" id="plantilla" class="block w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="">-- Seleccione --</option>
                    @foreach($this->plantillas as $plantilla)
                        <option value="{{ $plantilla->id }}">{{ $plantilla->nombre }} ({{ $plantilla->ejercicios->count() }} ejercicios)</option>
                    @endforeach
                </select>
                @error('selectedPlantillaId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button wire:click="$set('showAssignModal', false)">
                    Cancelar
                </x-secondary-button>

                <x-primary-button class="ml-3" wire:click="assignPlantilla">
                    Asignar y Copiar
                </x-primary-button>
            </div>
        </div>
    </x-modal>
</div>
