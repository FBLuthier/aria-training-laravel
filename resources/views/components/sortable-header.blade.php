@props(['field', 'currentField', 'direction' => 'asc'])

@php
    $isActive = $currentField === $field;
    $icon = $isActive ? ($direction === 'asc' ? '↑' : '↓') : '';
@endphp

<th {{ $attributes->merge(['class' => 'px-6 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600']) }}>
    <button 
        wire:click="sortBy('{{ $field }}')" 
        class="flex items-center gap-2 w-full text-left font-medium {{ $isActive ? 'text-blue-600 dark:text-blue-400' : '' }}"
    >
        {{ $slot }}
        @if($isActive)
            <span class="text-xs">{{ $icon }}</span>
        @endif
    </button>
</th>
