@props([
    'message' => 'Cargando...',
    'target' => null // Wire:loading target específico
])

<div 
    {{ $attributes->merge(['class' => 'fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 dark:bg-gray-950/70']) }}
    @if($target)
        wire:loading.flex
        wire:target="{{ $target }}"
    @else
        wire:loading.flex
    @endif
    style="display: none;"
>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 flex flex-col items-center gap-4">
        <x-spinner size="xl" color="primary" />
        <p class="text-gray-700 dark:text-gray-300 font-medium">{{ $message }}</p>
    </div>
</div>
