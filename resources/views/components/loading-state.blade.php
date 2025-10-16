@props([
    'target' => null,
    'message' => 'Cargando...',
    'inline' => false
])

@if($inline)
    {{-- Loading inline (para inputs, búsquedas, etc.) --}}
    <div 
        {{ $attributes->merge(['class' => 'flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400']) }}
        @if($target)
            wire:loading.flex
            wire:target="{{ $target }}"
        @else
            wire:loading.flex
        @endif
        style="display: none;"
    >
        <x-spinner size="sm" color="gray" />
        <span>{{ $message }}</span>
    </div>
@else
    {{-- Loading block (para contenido completo) --}}
    <div 
        {{ $attributes->merge(['class' => 'flex items-center justify-center gap-3 p-4 text-gray-600 dark:text-gray-400']) }}
        @if($target)
            wire:loading.flex
            wire:target="{{ $target }}"
        @else
            wire:loading.flex
        @endif
        style="display: none;"
    >
        <x-spinner size="md" color="gray" />
        <span class="font-medium">{{ $message }}</span>
    </div>
@endif
