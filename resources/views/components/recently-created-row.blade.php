@props([
    'item',
])

{{-- Fila resaltada para item recién creado --}}
<tr class="bg-green-100 dark:bg-green-900 border-b border-green-200 dark:border-green-800">
    <td class="w-4 p-4"></td>
    
    {{-- Contenido del slot (columnas) --}}
    {{ $slot }}
    
    {{-- Acciones por defecto --}}
    @if(!isset($hideActions) || !$hideActions)
        <td class="px-6 py-4 text-right">
            <div class="flex gap-3 justify-end">
                <button wire:click="edit({{ $item->id }})" class="font-medium text-blue-600 dark:text-blue-500 hover:underline inline-flex items-center gap-1">
                    <x-spinner size="xs" color="current" wire:loading wire:target="edit({{ $item->id }})" style="display: none;" />
                    <span>Editar</span>
                </button>
                <button wire:click="delete({{ $item->id }})" class="font-medium text-red-600 dark:text-red-500 hover:underline inline-flex items-center gap-1">
                    <x-spinner size="xs" color="current" wire:loading wire:target="delete({{ $item->id }})" style="display: none;" />
                    <span>Eliminar</span>
                </button>
            </div>
        </td>
    @endif
</tr>
