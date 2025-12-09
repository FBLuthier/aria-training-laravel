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
                    
                    @if($bloques->isEmpty() && $dia->rutinaEjercicios->whereNull('rutina_bloque_id')->isEmpty())
                        <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                            <p>No hay ejercicios asignados a este día.</p>
                            <p class="text-sm">Usa el buscador de la derecha para añadir ejercicios.</p>
                            <button wire:click="createBloque" class="mt-4 text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                + Añadir Bloque (Sección)
                            </button>
                        </div>
                    @else
                        <div class="space-y-8">
                            {{-- 1. BLOQUES (Sortable) --}}
                            <div x-data="{
                                initSortable() {
                                    let el = this.$el;
                                    window.Sortable.create(el, {
                                        handle: '.bloque-handle',
                                        animation: 150,
                                        ghostClass: 'bg-indigo-50',
                                        onEnd: (evt) => {
                                            let items = Array.from(el.children).map(child => child.dataset.id);
                                            $wire.reorderBloques(items);
                                        }
                                    });
                                }
                            }" x-init="initSortable()">
                                @foreach($bloques as $bloque)
                                    <div wire:key="bloque-{{ $bloque->id }}" 
                                         data-id="{{ $bloque->id }}"
                                         x-data="{ open: false }" 
                                         class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-6"
                                    >
                                        {{-- Cabecera del Bloque (Click para abrir/cerrar) --}}
                                        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center cursor-pointer"
                                             @click="open = !open"
                                        >
                                            <div class="flex items-center gap-3 flex-1">
                                                {{-- Handle para mover bloque --}}
                                                <div class="bloque-handle cursor-move text-gray-400 hover:text-gray-600 p-1" @click.stop>
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                                                </div>

                                                {{-- Icono Chevron --}}
                                                <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" 
                                                     :class="{'rotate-180': open}"
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                >
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
    
                                                {{-- Input Nombre Bloque (Stop propagation para no cerrar al editar) --}}
                                                <div class="flex-1" @click.stop>
                                                    <input type="text" 
                                                           value="{{ $bloque->nombre }}"
                                                           wire:change="updateBloqueNombre({{ $bloque->id }}, $event.target.value)"
                                                           class="font-bold text-lg text-gray-800 dark:text-gray-200 bg-transparent border-none focus:ring-0 p-0 w-full hover:bg-gray-200 dark:hover:bg-gray-600 rounded px-2 transition-colors"
                                                    />
                                                </div>
                                            </div>
    
                                            <div class="flex items-center gap-4">
                                                <span class="text-xs text-gray-500" x-show="!open">
                                                    {{ $bloque->rutinaEjercicios->count() }} ejercicios
                                                </span>
                                                <button wire:click="deleteBloque({{ $bloque->id }})" 
                                                        @click.stop
                                                        class="text-xs text-red-500 hover:text-red-700 font-medium" 
                                                        title="Eliminar Sección"
                                                >
                                                    Eliminar Sección
                                                </button>
                                            </div>
                                        </div>
    
                                        {{-- Lista de Ejercicios del Bloque (Colapsable y Sortable) --}}
                                        <div x-show="open" 
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                                             x-transition:enter-end="opacity-100 transform translate-y-0"
                                             class="p-4 space-y-4 bg-white dark:bg-gray-800"
                                             style="display: none;"
                                             x-data="{
                                                initSortableEjercicios() {
                                                    let el = this.$el;
                                                    window.Sortable.create(el, {
                                                        group: 'ejercicios',
                                                        filter: '.no-drag',
                                                        preventOnFilter: false,
                                                        animation: 150,
                                                        ghostClass: 'bg-indigo-50',
                                                        onEnd: (evt) => {
                                                            // Recolectar info de todos los ejercicios en este contenedor
                                                            let items = Array.from(el.children).map((child, index) => {
                                                                return {
                                                                    id: child.dataset.id,
                                                                    bloque_id: '{{ $bloque->id }}',
                                                                    orden: index + 1
                                                                };
                                                            });
                                                            // Si se movió a otro contenedor, el evento se dispara en el origen, 
                                                            // pero necesitamos actualizar ambos o manejarlo globalmente.
                                                            // Mejor estrategia: enviar el item movido y su nuevo destino.
                                                            // O simplemente enviar el nuevo estado de ESTA lista.
                                                            
                                                            // Estrategia robusta: Enviar actualización del elemento movido
                                                            let itemEl = evt.item;
                                                            let newBloqueId = evt.to.dataset.bloqueId || null;
                                                            let newIndex = evt.newIndex;
                                                            
                                                            $wire.reorderEjercicio(itemEl.dataset.id, newBloqueId, newIndex + 1);
                                                        }
                                                    });
                                                }
                                             }"
                                             x-init="initSortableEjercicios()"
                                             data-bloque-id="{{ $bloque->id }}"
                                        >
                                            @forelse($bloque->rutinaEjercicios as $re)
                                                <div wire:key="ejercicio-{{ $re->id }}" data-id="{{ $re->id }}">
                                                    @include('livewire.admin.partials.ejercicio-card', ['re' => $re])
                                                </div>
                                            @empty
                                                <div class="text-sm text-gray-400 italic py-4 text-center border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-lg">
                                                    <p>Sección vacía.</p>
                                                    <p class="text-xs mt-1">Arrastra ejercicios aquí o añádelos desde el buscador seleccionando "{{ $bloque->nombre }}".</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- 2. EJERCICIOS SIN BLOQUE (GENERAL) --}}
                            @php
                                $ejerciciosSinBloque = $dia->rutinaEjercicios->whereNull('rutina_bloque_id');
                            @endphp

                            <div class="border-l-4 border-gray-300 dark:border-gray-600 pl-4" x-data="{ open: false }">
                                <div class="flex justify-between items-center cursor-pointer mb-4" @click="open = !open">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" 
                                             :class="{'rotate-180': open}"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                        <h4 class="font-bold text-gray-600 dark:text-gray-400">General / Sin Sección</h4>
                                    </div>
                                    <span class="text-xs text-gray-500" x-show="!open">
                                        {{ $ejerciciosSinBloque->count() }} ejercicios
                                    </span>
                                </div>

                                <div class="space-y-4 min-h-[50px]"
                                     x-show="open"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                                     x-transition:enter-end="opacity-100 transform translate-y-0"
                                     style="display: none;"
                                     x-data="{
                                        initSortableGeneral() {
                                            let el = this.$el;
                                            window.Sortable.create(el, {
                                                group: 'ejercicios',
                                                filter: '.no-drag',
                                                preventOnFilter: false,
                                                animation: 150,
                                                ghostClass: 'bg-indigo-50',
                                                onEnd: (evt) => {
                                                    let itemEl = evt.item;
                                                    // Obtener el ID del bloque destino dinámicamente.
                                                    // Si se mueve a otro bloque, evt.to tendrá el dataset.bloqueId del destino.
                                                    // Si se queda en General, evt.to es el mismo el, y bloqueId será vacío/undefined, por lo que usamos null.
                                                    let newBloqueId = evt.to.dataset.bloqueId || null; 
                                                    let newIndex = evt.newIndex;
                                                    $wire.reorderEjercicio(itemEl.dataset.id, newBloqueId, newIndex + 1);
                                                }
                                            });
                                        }
                                     }"
                                     x-init="initSortableGeneral()"
                                     data-bloque-id=""
                                >
                                    @foreach($ejerciciosSinBloque as $re)
                                        <div wire:key="ejercicio-{{ $re->id }}" data-id="{{ $re->id }}">
                                            @include('livewire.admin.partials.ejercicio-card', ['re' => $re])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            {{-- Botón Añadir Bloque al final --}}
                            <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                                <button wire:click="createBloque" class="flex items-center text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Añadir Nueva Sección
                                </button>
                            </div>
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
                    
                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Añadir a Sección:</label>
                        <select wire:model.live="selectedBloqueId" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">General (Sin Sección)</option>
                            @foreach($bloques as $bloque)
                                <option value="{{ $bloque->id }}">{{ $bloque->nombre }}</option>
                            @endforeach
                        </select>
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
                                    <button wire:click="addEjercicio({{ $ejercicio->id }}, $wire.selectedBloqueId)" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors border-b border-gray-100 dark:border-gray-600 last:border-0">
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
