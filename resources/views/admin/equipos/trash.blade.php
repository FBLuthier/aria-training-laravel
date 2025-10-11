<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Papelera de Equipos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Botón para Volver a la Lista Principal --}}
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('admin.equipos.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:underline">
                            &larr; Volver a la Lista de Equipos
                        </a>
                    </div>
                    
                    {{-- INICIO: Tabla de Equipos en Papelera --}}
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">ID</th>
                                    <th scope="col" class="px-6 py-3">Nombre</th>
                                    <th scope="col" class="px-6 py-3">Fecha de Eliminación</th>
                                    <th scope="col" class="px-6 py-3"><span class="sr-only">Acciones</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($equipos as $equipo)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $equipo->id }}</th>
                                    <td class="px-6 py-4">{{ $equipo->nombre }}</td>
                                    <td class="px-6 py-4">{{ $equipo->deleted_at->format('d/m/Y H:i') }}</td>
                                    
                                    <td class="px-6 py-4 text-right" x-data="{ showModal: false }">
                                        <div class="flex gap-4 justify-end">
                                            {{-- Botón Restaurar --}}
                                            <form method="POST" action="{{ route('admin.equipos.restore', $equipo->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="font-medium text-green-600 dark:text-green-500 hover:underline">Restaurar</button>
                                            </form>

                                            {{-- Botón que ABRE el modal para Eliminar Permanentemente --}}
                                            <button @click="showModal = true" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                                                Eliminar Permanentemente
                                            </button>
                                        </div>

                                        {{-- Modal de Confirmación para Borrado Permanente --}}
                                        <div x-show="showModal" x-cloak 
                                             class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto"
                                             aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                            
                                            <div @click="showModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                                            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl transform transition-all sm:my-8 sm:w-full sm:max-w-lg p-6">
                                                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100" id="modal-title">
                                                    Confirmar Eliminación Permanente
                                                </h3>
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        ¿Estás seguro de que deseas eliminar permanentemente el equipo "{{ $equipo->nombre }}"? Esta acción no se puede deshacer.
                                                    </p>
                                                </div>
                                                <div class="mt-6 flex justify-end gap-4">
                                                    <x-secondary-button @click="showModal = false">Cancelar</x-secondary-button>
                                                    <form method="POST" action="{{ route('admin.equipos.forceDelete', $equipo->id) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <x-danger-button type="submit">Sí, Eliminar Permanentemente</x-danger-button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center">La papelera está vacía.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- FIN: Tabla de Equipos --}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>