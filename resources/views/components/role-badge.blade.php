@props(['role'])

@php
    // Si role es un entero (ID), intentamos convertirlo a Enum
    if (is_int($role)) {
        $role = \App\Enums\UserRole::tryFrom($role);
    }
    
    $color = $role?->color() ?? 'gray';
    $label = $role?->label() ?? 'Desconocido';
    
    $colors = [
        'indigo' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
        'emerald' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-300',
        'blue' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        'gray' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
    ];
    
    $classes = $colors[$color] ?? $colors['gray'];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes }}">
    {{ $label }}
</span>
