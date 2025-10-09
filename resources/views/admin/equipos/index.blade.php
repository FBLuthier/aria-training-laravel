<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Equipos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- INICIO: Botón para Crear Equipo --}}
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('admin.equipos.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Crear Equipo
                        </a>
                    </div>
                    {{-- FIN: Botón para Crear Equipo --}}


                    
                    {{-- INICIO: Tabla de Equipos --}}
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            {{-- Cabecera de la Tabla --}}
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        ID
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Nombre
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                    <span class="sr-only">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            {{-- Cuerpo de la Tabla --}}
                            <tbody>
                                @forelse ($equipos as $equipo)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $equipo->id }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ $equipo->nombre }}
                                    </td>
                                    {{-- INICIO: Celda de Acciones Corregida --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex gap-4 justify-end">
                                            <a href="{{ route('admin.equipos.edit', $equipo) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Editar</a>
                                            
                                            <form method="POST" action="{{ route('admin.equipos.destroy', $equipo) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline" onclick="return confirm('¿Estás seguro de que deseas eliminar este equipo?')">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    {{-- FIN: Celda de Acciones Corregida --}}
                                </tr>
                                @empty
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td colspan="3" class="px-6 py-4 text-center">
                                        No hay equipos registrados.
                                    </td>
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