@props([
    'searchPlaceholder' => 'Buscar...',
    'createButtonText' => 'Crear Nuevo',
    'showingTrash' => false,
])

{{-- Barra de acciones y búsqueda --}}
<div class="flex justify-between items-center mb-4">
    <div class="flex items-center gap-4 w-1/3">
        {{-- Campo de búsqueda --}}
        <div class="relative w-full">
            <x-text-input 
                wire:model.live="search"
                class="block w-full" 
                type="text" 
                :placeholder="$searchPlaceholder" />
            <div class="absolute right-3 top-1/2 -translate-y-1/2">
                <x-spinner 
                    size="sm" 
                    color="gray"
                    wire:loading 
                    wire:target="search"
                    style="display: none;"
                />
            </div>
        </div>

        {{-- Bulk Actions --}}
        @if(!$showingTrash)
            <x-bulk-actions :selectedCount="$this->selectedCount">
                <a href="#" wire:click.prevent="confirmDeleteSelected" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600" role="menuitem">
                    Eliminar Seleccionados
                </a>
            </x-bulk-actions>
        @else
            <x-bulk-actions :selectedCount="$this->selectedCount">
                <a href="#" wire:click.prevent="confirmRestoreSelected" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600" role="menuitem">
                    Restaurar Seleccionados
                </a>
                <a href="#" wire:click.prevent="confirmForceDeleteSelected" class="block px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-600" role="menuitem">
                    Eliminar Permanentemente
                </a>
            </x-bulk-actions>
        @endif
    </div>
    
    {{-- Botones principales --}}
    <div class="flex gap-3">
        <x-secondary-button wire:click="toggleTrash" loadingTarget="toggleTrash">
            {{ $showingTrash ? 'Ver Activos' : 'Ver Papelera' }}
        </x-secondary-button>
        @if(!$showingTrash)
            <x-primary-button wire:click="create">
                {{ $createButtonText }}
            </x-primary-button>
        @endif
    </div>
</div>
