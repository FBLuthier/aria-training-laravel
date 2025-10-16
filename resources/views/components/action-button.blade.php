@props([
    'action' => '',
    'icon' => null,
    'color' => 'blue',
    'loadingTarget' => null
])

@php
    $colorClasses = [
        'blue' => 'text-blue-600 dark:text-blue-500',
        'red' => 'text-red-600 dark:text-red-500',
        'green' => 'text-green-600 dark:text-green-500',
        'yellow' => 'text-yellow-600 dark:text-yellow-500',
    ];
    
    $colorClass = $colorClasses[$color] ?? $colorClasses['blue'];
    $target = $loadingTarget ?? $action;
@endphp

<button 
    wire:click="{{ $action }}" 
    class="font-medium {{ $colorClass }} hover:underline inline-flex items-center gap-1"
    {{ $attributes }}
>
    @if($icon)
        <x-spinner 
            size="xs" 
            color="current" 
            wire:loading 
            wire:target="{{ $target }}" 
            style="display: none;" 
        />
    @endif
    <span>{{ $slot }}</span>
</button>
