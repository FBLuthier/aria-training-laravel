<div>
    {{-- El slot del header se encarga de poner el título en el layout principal --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Equipos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- INICIO: Botones y Búsqueda --}}
                    <div class="flex justify-between items-center mb-4">
                        <x-text-input 
                            wire:model.live="search"
                            class="block w-1/3" 
                            type="text" 
                            placeholder="Buscar equipo..." />

                        <div class="flex gap-4">
                            <a href="{{ route('admin.equipos.trash') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:underline self-center">
                                Ver Papelera
                            </a>
                            <a href="{{ route('admin.equipos.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Crear Equipo
                            </a>
                        </div>
                    </div>
                    {{-- FIN: Botones y Búsqueda --}}

                    {{-- INICIO: Tabla de Equipos --}}
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        {{-- INICIO: Botón de Ordenamiento --}}
                                        <button wire:click="sortBy('id')" class="flex items-center gap-2">
                                            ID
                                            {{-- Lógica de Iconos --}}
                                            @if ($sortField === 'id')
                                                <svg class="w-3 h-3" ...>
                                                    @if ($sortDirection === 'asc')
                                                        <path ... d="M5 13V1m0 0L1 5m4-4 4 4"/>
                                                    @else
                                                        <path ... d="M5 1v12m0 0 4-4m-4 4L1 9"/>
                                                    @endif
                                                </svg>
                                            @endif
                                        </button>
                                        {{-- FIN: Botón de Ordenamiento --}}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        {{-- INICIO: Botón de Ordenamiento --}}
                                        <button wire:click="sortBy('nombre')" class="flex items-center gap-2">
                                            Nombre
                                            {{-- Lógica de Iconos --}}
                                            @if ($sortField === 'nombre')
                                                <svg class="w-3 h-3" ...>
                                                    @if ($sortDirection === 'asc')
                                                        <path ... d="M5 13V1m0 0L1 5m4-4 4 4"/>
                                                    @else
                                                        <path ... d="M5 1v12m0 0 4-4m-4 4L1 9"/>
                                                    @endif
                                                </svg>
                                            @endif
                                        </button>
                                        {{-- FIN: Botón de Ordenamiento --}}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        <span class="sr-only">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($equipos as $equipo)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $equipo->id }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ $equipo->nombre }}
                                    </td>
                                    <td class="px-6 py-4 text-right" x-data="{ showModal: false }">
                                        <div class="flex gap-4 justify-end">
                                            <a href="{{ route('admin.equipos.edit', $equipo) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Editar</a>
                                            <button @click="showModal = true" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                                                Eliminar
                                            </button>
                                        </div>
                                        {{-- Modal de Confirmación --}}
                                        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                            <div @click="showModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                                            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl transform transition-all sm:my-8 sm:w-full sm:max-w-lg p-6">
                                                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100" id="modal-title">
                                                    Confirmar Eliminación
                                                </h3>
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        ¿Estás seguro de que deseas eliminar el equipo "{{ $equipo->nombre }}"? Esta acción no se puede deshacer.
                                                    </p>
                                                </div>
                                                <div class="mt-6 flex justify-end gap-4">
                                                    <x-secondary-button @click="showModal = false">Cancelar</x-secondary-button>
                                                    <form method="POST" action="{{ route('admin.equipos.destroy', $equipo) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <x-danger-button type="submit">Sí, Eliminar</x-danger-button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td colspan="3" class="px-6 py-4 text-center">
                                        No hay equipos registrados que coincidan con la búsqueda.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- FIN: Tabla de Equipos --}}

                    {{-- INICIO: Bloque de Paginación --}}
                    <div class="mt-4">
                        {{ $equipos->links() }}
                    </div>
                    {{-- FIN: Bloque de Paginación --}}
                </div>
            </div>
        </div>
    </div>
</div>