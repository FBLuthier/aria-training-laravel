@props(['active'])

@php
    $classes = $active 
        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' 
        : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
    
    $label = $active ? 'Activo' : 'Inactivo';
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes }}">
    <span class="w-2 h-2 mr-1 rounded-full {{ $active ? 'bg-green-400' : 'bg-red-400' }}"></span>
    {{ $label }}
</span>
