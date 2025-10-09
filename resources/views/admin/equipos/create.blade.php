<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Nuevo Equipo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- INICIO: Formulario de Creación --}}
                    <form method="POST" action="{{ route('admin.equipos.store') }}">
                        @csrf

                        {{-- Campo: Nombre del Equipo --}}
                        <div>
                            <x-input-label for="nombre" :value="__('Nombre del Equipo')" />
                            <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre')" required autofocus />
                            <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                        </div>

                        {{-- Botones de Acción --}}
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.equipos.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                Cancelar
                            </a>

                            <x-primary-button class="ms-4">
                                {{ __('Guardar Equipo') }}
                            </x-primary-button>
                        </div>
                    </form>
                    {{-- FIN: Formulario de Creación --}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>