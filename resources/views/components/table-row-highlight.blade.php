@props(['wireKey', 'highlighted' => false])

<tr 
    wire:key="{{ $wireKey }}" 
    class="{{ $highlighted 
        ? 'bg-green-50 dark:bg-green-900/20 border-2 border-green-500 dark:border-green-400' 
        : 'bg-white border-b dark:bg-gray-800 dark:border-gray-700' 
    }} hover:bg-gray-50 dark:hover:bg-gray-600"
    {{ $attributes }}
>
    {{ $slot }}
</tr>
