<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("¡Bienvenido al Sistema de Gestión!") }}

                    <!-- BOTONES PROVISIONALES DE NAVEGACIÓN -->
                    <div class="mt-8 space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Navegación Rápida</h3>

                        <div class="flex flex-wrap gap-4">
                            <!-- BOTÓN PARA EQUIPOS -->
                            <a href="{{ route('admin.equipos.index') }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Gestión de Equipos
                            </a>

                            <!-- BOTÓN PARA AUDITORÍAS -->
                            <a href="{{ route('admin.auditoria.index') }}"
                               class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Gestión de Auditoría
                            </a>
                        </div>

                        <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                            <p>💡 <strong>Estos botones son provisionales</strong> para facilitar la navegación durante el desarrollo.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
