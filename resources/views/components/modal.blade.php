@props([
    'show' => false,
    'maxWidth' => '2xl',
    'entangleProperty' => null,
    'name' => null,
    'focusable' => false
])

@php
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    '3xl' => 'sm:max-w-3xl',
    '4xl' => 'sm:max-w-4xl',
    '5xl' => 'sm:max-w-5xl',
    '6xl' => 'sm:max-w-6xl',
][$maxWidth];

// Convertir el valor de show a booleano
$showModal = filter_var($show, FILTER_VALIDATE_BOOLEAN);
@endphp

@if($showModal)
    {{-- Fondo oscuro --}}
    <div
        class="fixed inset-0 z-50"
        style="background-color: rgba(0, 0, 0, 0.5);"
        wire:ignore.self
    >
        {{-- Contenido del modal --}}
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative w-full {{ $maxWidth }} bg-white dark:bg-gray-800 rounded-lg shadow-xl">
                {{-- Botón cerrar --}}
                @if($entangleProperty)
                    <button
                        type="button"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 z-10"
                        wire:click="$set('{{ $entangleProperty }}', false)"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                @endif

                {{-- Contenido del modal --}}
                <div class="p-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
@endif
