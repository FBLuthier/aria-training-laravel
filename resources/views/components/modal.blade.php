@props([
    'show' => false,
    'maxWidth' => '2xl',
    'entangleProperty' => 'show'
])

@php
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth];
@endphp

{{-- Modal controlado por Livewire --}}
@if($show)
<div
    x-data="{
        entangleProperty: @js($entangleProperty),
        close() {
            if (this.entangleProperty) {
                this.$wire.call('closeModal', this.entangleProperty);
            }
        },
        closeOnEscape(event) {
            if (event.key === 'Escape') {
                this.close();
            }
        }
    }"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
    x-on:click.self="close()"
    x-on:keydown.window="closeOnEscape($event)"
>
    {{-- Fondo oscuro --}}
    <div
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"
    ></div>

    {{-- Contenido del modal --}}
    <div
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="mb-6 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full {{ $maxWidth }} sm:mx-auto relative"
    >
        {{-- Botón X para cerrar (arriba a la derecha) --}}
        <button
            type="button"
            @click="close()"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors z-10"
            aria-label="Cerrar modal"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        {{-- Contenido del modal --}}
        <div class="relative">
            {{ $slot }}
        </div>
    </div>
</div>
@endif
