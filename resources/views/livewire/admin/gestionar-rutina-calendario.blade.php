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

    {{-- Modal de Confirmación: Eliminar Día --}}
    <x-confirmation-modal :show="$confirmingDiaDeletion" entangleProperty="confirmingDiaDeletion">
        <x-slot name="title">Eliminar Día</x-slot>
        <x-slot name="content">
            ¿Estás seguro de que deseas eliminar este día? Se eliminarán también todos los ejercicios asociados.
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmingDiaDeletion', false)">Cancelar</x-secondary-button>
            <x-danger-button class="ml-3" wire:click="performDeleteDia" loadingTarget="performDeleteDia">Eliminar</x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Info Rutina --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p><strong>Atleta:</strong> {{ $rutina->atleta?->nombre_1 ?? 'Sin Asignar' }} {{ $rutina->atleta?->apellido_1 }}</p>
                    @if($rutina->descripcion)
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $rutina->descripcion }}</p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-12 gap-6">
                
                {{-- COLUMNA IZQUIERDA: BANCO DE DÍAS --}}
                <div class="col-span-12 lg:col-span-3">
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4 sticky top-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-gray-700 dark:text-gray-300">Banco de Días</h3>
                            <button wire:click="addDia" class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white py-1 px-2 rounded">
                                + Nuevo
                            </button>
                        </div>
                        
                        <div class="space-y-3 min-h-[200px]" 
                             x-data 
                             @dragover.prevent 
                             @drop.prevent="$wire.removeFecha($event.dataTransfer.getData('diaId'))"
                        >
                            @forelse($this->diasSinFecha as $dia)
                                <div draggable="true" 
                                     @dragstart="$event.dataTransfer.setData('diaId', {{ $dia->id }})"
                                     class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded p-3 cursor-move hover:shadow-md transition-shadow group"
                                >
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <input type="text" 
                                                   value="{{ $dia->nombre_dia }}" 
                                                   wire:change="updateDiaNombre({{ $dia->id }}, $event.target.value)"
                                                   class="font-semibold text-sm text-gray-800 dark:text-gray-200 bg-transparent border-none focus:ring-0 p-0 w-full"
                                            />
                                            <div class="text-xs text-gray-500 mt-1">{{ $dia->rutinaEjercicios->count() }} ejercicios</div>
                                        </div>
                                        <div class="flex flex-col gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('admin.rutinas.dia', $dia->id) }}" class="text-indigo-500 hover:text-indigo-700" title="Editar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            <button wire:click="duplicateDia({{ $dia->id }})" class="text-blue-500 hover:text-blue-700" title="Duplicar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                            </button>
                                            <button wire:click="confirmDeleteDia({{ $dia->id }})" class="text-red-500 hover:text-red-700" title="Eliminar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-xs text-gray-400 text-center py-4 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded">
                                    No hay días en el banco.
                                </div>
                            @endforelse
                            
                            <div class="text-xs text-gray-400 mt-4 text-center">
                                <p>Arrastra los días al calendario para programarlos.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- COLUMNA DERECHA: CALENDARIO --}}
                <div class="col-span-12 lg:col-span-9">
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                        
                        {{-- Navegación Calendario --}}
                        <div class="flex justify-between items-center mb-6">
                            <button wire:click="changeMonth(-1)" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </button>
                            
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 capitalize">
                                {{ \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->locale('es')->isoFormat('MMMM YYYY') }}
                            </h3>
                            
                            <button wire:click="changeMonth(1)" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>

                        {{-- Grid Calendario --}}
                        <div class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-700 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            
                            {{-- Cabeceras Días --}}
                            @foreach(['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $dayName)
                                <div class="bg-gray-50 dark:bg-gray-800 p-2 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ $dayName }}
                                </div>
                            @endforeach

                            {{-- Días del Mes --}}
                            @php
                                $firstDay = \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1);
                                $daysInMonth = $firstDay->daysInMonth;
                                $startPadding = ($firstDay->dayOfWeekIso - 1); // 0 for Monday
                                $endPadding = (7 - ($startPadding + $daysInMonth) % 7) % 7;
                            @endphp

                            {{-- Padding Inicio --}}
                            @for($i = 0; $i < $startPadding; $i++)
                                <div class="bg-white dark:bg-gray-900 min-h-[120px]"></div>
                            @endfor

                            {{-- Días --}}
                            @for($day = 1; $day <= $daysInMonth; $day++)
                                @php
                                    $dateStr = \Carbon\Carbon::createFromDate($currentYear, $currentMonth, $day)->format('Y-m-d');
                                    $isToday = $dateStr === now()->format('Y-m-d');
                                    $diasDelDia = $this->diasProgramados[$dateStr] ?? collect();
                                @endphp

                                <div class="bg-white dark:bg-gray-900 min-h-[120px] p-2 transition-colors hover:bg-gray-50 dark:hover:bg-gray-800 relative group/cell"
                                     x-data
                                     @dragover.prevent="$el.classList.add('bg-indigo-50', 'dark:bg-indigo-900/30')"
                                     @dragleave="$el.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/30')"
                                     @drop.prevent="
                                        $el.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/30');
                                        $wire.assignFecha($event.dataTransfer.getData('diaId'), '{{ $dateStr }}')
                                     "
                                >
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="text-sm font-medium {{ $isToday ? 'bg-indigo-600 text-white w-6 h-6 rounded-full flex items-center justify-center' : 'text-gray-700 dark:text-gray-300' }}">
                                            {{ $day }}
                                        </span>
                                    </div>

                                    {{-- Días Asignados --}}
                                    <div class="space-y-2">
                                        @foreach($diasDelDia as $dia)
                                            <div draggable="true"
                                                 @dragstart="$event.dataTransfer.setData('diaId', {{ $dia->id }})"
                                                 class="bg-indigo-100 dark:bg-indigo-900/50 border border-indigo-200 dark:border-indigo-700 rounded p-2 text-xs cursor-move group"
                                            >
                                                <div class="font-semibold text-indigo-800 dark:text-indigo-200 truncate">
                                                    {{ $dia->nombre_dia }}
                                                </div>
                                                <div class="text-indigo-600 dark:text-indigo-400 text-[10px]">
                                                    {{ $dia->rutinaEjercicios->count() }} ejercicios
                                                </div>

                                                {{-- Acciones Rápidas (Hover en celda) --}}
                                                <div class="absolute right-1 hidden group-hover/cell:flex gap-1 bg-white dark:bg-gray-800 rounded shadow-sm p-0.5 z-10 border border-gray-200 dark:border-gray-700"
                                                     style="top: {{ 4 + ($loop->index * 28) }}px">
                                                    
                                                    <a href="{{ route('admin.rutinas.dia', $dia->id) }}" class="p-1 text-gray-500 hover:text-indigo-600" title="Editar">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                    </a>
                                                    <button wire:click="copyToNextWeek({{ $dia->id }})" class="p-1 text-gray-500 hover:text-blue-600" title="Copiar a semana siguiente">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                                    </button>
                                                    <button wire:click="confirmDeleteDia({{ $dia->id }})" class="p-1 text-gray-500 hover:text-red-600" title="Eliminar">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endfor

                            {{-- Padding Fin --}}
                            @for($i = 0; $i < $endPadding; $i++)
                                <div class="bg-white dark:bg-gray-900 min-h-[120px]"></div>
                            @endfor

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
