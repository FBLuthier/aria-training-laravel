<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $showingTrash ? 'Papelera de Equipos' : 'Gestión de Equipos' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- ======================================================================= --}}
                    {{-- BARRA DE ACCIONES Y BÚSQUEDA                                            --}}
                    {{-- ======================================================================= --}}
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-4 w-1/3">
                            {{-- Campo de búsqueda --}}
                            <x-text-input wire:model.live="search" class="block w-full" type="text" placeholder="Buscar equipo..." />

                            {{-- Menú de acciones en lote (unificado) --}}
                            @if(count($selectedEquipos) > 0)
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                                        <span>Acciones ({{ count($selectedEquipos) }})</span>
                                        <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" class="origin-top-left absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 z-10">
                                        <div class="py-1">
                                            @if ($showingTrash)
                                                {{-- Acciones para la papelera --}}
                                                <a href="#" wire:click.prevent="confirmRestoreSelected" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Restaurar Seleccionados</a>
                                                <a href="#" wire:click.prevent="confirmForceDeleteSelected" class="block px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-600">Eliminar Permanentemente</a>
                                            @else
                                                {{-- Acciones para la vista activa --}}
                                                <a href="#" wire:click.prevent="confirmDeleteSelected" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Eliminar Seleccionados</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="flex gap-4">
                            {{-- Botón para alternar entre vista activa y papelera --}}
                            <button wire:click="toggleTrash" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-sm">{{ $showingTrash ? 'Ver Activos' : 'Ver Papelera' }}</button>
                            
                            {{-- Botón para abrir el modal de creación --}}
                            @if (!$showingTrash)
                                <button wire:click="crear" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Crear Equipo</button>
                            @endif
                        </div>
                    </div>

                    {{-- ======================================================================= --}}
                    {{-- TABLA DE EQUIPOS                                                        --}}
                    {{-- ======================================================================= --}}
                    <x-table>
                        <x-slot name="thead">
                            <tr>
                                <th scope="col" class="p-4"><input wire:model.live="selectAll" type="checkbox"></th>
                                <th scope="col" class="px-6 py-3">
                                    <button wire:click="sortBy('id')" class="flex items-center gap-2">
                                        ID
                                        <div class="w-3 h-3">
                                            @if ($sortField === 'id')
                                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 14">
                                                    @if ($sortDirection->value === 'asc')
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13V1m0 0L1 5m4-4 4 4"/>
                                                    @else
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1v12m0 0 4-4m-4 4L1 9"/>
                                                    @endif
                                                </svg>
                                            @endif
                                        </div>
                                    </button>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <button wire:click="sortBy('nombre')" class="flex items-center gap-2">
                                        Nombre
                                        <div class="w-3 h-3">
                                            @if ($sortField === 'nombre')
                                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 14">
                                                    @if ($sortDirection->value === 'asc')
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13V1m0 0L1 5m4-4 4 4"/>
                                                    @else
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1v12m0 0 4-4m-4 4L1 9"/>
                                                    @endif
                                                </svg>
                                            @endif
                                        </div>
                                    </button>
                                </th>
                                <th scope="col" class="px-6 py-3"><span class="sr-only">Acciones</span></th>
                            </tr>
                        </x-slot>
                        <x-slot name="tbody">
                            @forelse ($equipos as $equipo)
                                {{-- La clase condicional resalta la fila si es el equipo recién creado/editado --}}
                                <tr wire:key="equipo-{{ $equipo->id }}" class="@if($equipo->id === $equipoRecienCreado?->id) bg-green-100 dark:bg-green-900 @endif">
                                    <td class="w-4 p-4"><input wire:model.live="selectedEquipos" value="{{ $equipo->id }}" type="checkbox"></td>
                                    <th scope="row" class="px-6 py-4">{{ $equipo->id }}</th>
                                    <td class="px-6 py-4">{{ $equipo->nombre }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex gap-4 justify-end">
                                            {{-- Botón para abrir el modal de edición --}}
                                            <button wire:click="editar({{ $equipo->id }})" class="font-medium text-blue-600">Editar</button>
                                            <button wire:click="confirmAction({{ $equipo->id }}, 'deleteEquipo')" class="font-medium text-red-600">Eliminar</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-4 text-center">No hay equipos para mostrar.</td></tr>
                            @endforelse
                        </x-slot>
                    </x-table>

                    {{-- ======================================================================= --}}
                    {{-- MODAL UNIFICADO PARA CREAR Y EDITAR                                     --}}
                    {{-- ======================================================================= --}}
                    <x-modal name="equipo-form-modal" :show="$showModal" @keydown.escape.window="$wire.set('showModal', false)" focusable>
                        @include('livewire.admin.equipos._form')
                    </x-modal>

                    {{-- ======================================================================= --}}
                    {{-- MODALES DE CONFIRMACIÓN (Usan el Trait WithModalManagement)          --}}
                    {{-- ======================================================================= --}}
                    @if ($modalConfirmingId && $modalActionName === 'deleteEquipo')
                       {{-- ... --}}
                    @endif
                    @if ($modalConfirmingId && $modalActionName === 'restoreEquipo')
                       {{-- ... --}}
                    @endif
                    @if ($modalConfirmingId && $modalActionName === 'forceDeleteEquipo')
                       {{-- ... --}}
                    @endif
                    @if ($confirmingBulkDelete)
                       {{-- ... --}}
                    @endif
                    @if ($confirmingBulkRestore)
                       {{-- ... --}}
                    @endif
                    @if ($confirmingBulkForceDelete)
                       {{-- ... --}}
                    @endif
                    
                    {{-- ======================================================================= --}}
                    {{-- PAGINACIÓN                                                              --}}
                    {{-- ======================================================================= --}}
                    <div class="mt-4">
                        {{ $equipos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>