@props([
    'entityName' => 'registro',
    'entityNamePlural' => 'registros',
])

{{-- Banner: Selección de página actual --}}
@if($selectAll && !$selectingAll && count($selectedItems) > 0)
    <div class="mb-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm text-blue-800 dark:text-blue-200">
                    Se han seleccionado <strong>{{ count($selectedItems) }} {{ $entityNamePlural }}</strong> en esta página.
                </p>
            </div>
            <button 
                wire:click="selectAllRecords" 
                class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 underline"
            >
                Seleccionar todos los {{ $this->totalFilteredCount }} {{ $entityNamePlural }} que coinciden con los filtros
            </button>
        </div>
    </div>
@endif

{{-- Banner: Selección total con filtros --}}
@if($selectingAll)
    <div class="mb-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm text-green-800 dark:text-green-200">
                    Se han seleccionado <strong>todos los {{ $this->selectedCount }} {{ $entityNamePlural }}</strong> que coinciden con los filtros actuales.
                </p>
            </div>
            <button 
                wire:click="selectOnlyPage" 
                class="text-sm font-medium text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 underline"
            >
                Seleccionar solo esta página
            </button>
        </div>
    </div>
@endif
