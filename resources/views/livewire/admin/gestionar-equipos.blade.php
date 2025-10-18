<div>
    {{-- Título de la página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $showingTrash ? 'Papelera de Equipos' : 'Gestión de Equipos' }}
        </h2>
    </x-slot>

    {{-- Contenedor principal --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Barra de acciones y búsqueda --}}
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-4 w-1/3">
                            {{-- Campo de búsqueda --}}
                            <div class="relative w-full">
                                <x-text-input 
                                    wire:model.live="search"
                                    class="block w-full" 
                                    type="text" 
                                    placeholder="Buscar equipo..." />
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

                            {{-- Acciones en lote para equipos activos --}}
                            @if(!$showingTrash)
                                <x-bulk-actions :selectedCount="$this->selectedCount">
                                    <a href="#" wire:click.prevent="confirmDeleteSelected" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600" role="menuitem">
                                        Eliminar Seleccionados
                                    </a>
                                </x-bulk-actions>
                            @endif
                            
                            {{-- Acciones en lote para papelera --}}
                            @if($showingTrash)
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
                                    Crear Equipo
                                </x-primary-button>
                            @endif
                        </div>
                    </div>

                    {{-- Banner de selección masiva --}}
                    @if($selectAll && !$selectingAll && count($selectedItems) > 0)
                        <div class="mb-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-sm text-blue-800 dark:text-blue-200">
                                        Se han seleccionado <strong>{{ count($selectedItems) }} equipos</strong> en esta página.
                                    </p>
                                </div>
                                <button 
                                    wire:click="selectAllRecords" 
                                    class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 underline"
                                >
                                    Seleccionar todos los {{ $this->totalFilteredCount }} equipos que coinciden con los filtros
                                </button>
                            </div>
                        </div>
                    @endif

                    @if($selectingAll)
                        <div class="mb-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-sm text-green-800 dark:text-green-200">
                                        Se han seleccionado <strong>todos los {{ $this->selectedCount }} equipos</strong> que coinciden con los filtros actuales.
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

                    {{-- Loading state para la tabla --}}
                    <x-loading-state 
                        target="search,toggleTrash,sortBy,gotoPage,previousPage,nextPage" 
                        message="Cargando equipos..."
                        class="my-4"
                    />

                    {{-- Tabla de equipos --}}
                    <div wire:loading.remove wire:target="search,toggleTrash,sortBy,gotoPage,previousPage,nextPage">
                    @if ($showingTrash)
                        {{-- Vista de la papelera --}}
                        <x-data-table>
                            <x-slot name="thead">
                                <tr>
                                    <th class="p-4">
                                        <input wire:model.live="selectAll" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    </th>
                                    <x-sortable-header field="id" :currentField="$sortField" :direction="$sortDirection->value">ID</x-sortable-header>
                                    <x-sortable-header field="nombre" :currentField="$sortField" :direction="$sortDirection->value">Nombre</x-sortable-header>
                                    <x-sortable-header field="deleted_at" :currentField="$sortField" :direction="$sortDirection->value">Fecha Eliminación</x-sortable-header>
                                    <th class="px-6 py-3 text-right"><span class="sr-only">Acciones</span></th>
                                </tr>
                            </x-slot>
                            <x-slot name="tbody">
                                @forelse ($this->items as $equipo)
                                    <tr wire:key="trash-{{ $equipo->id }}" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="w-4 p-4">
                                            <input wire:model.live="selectedItems" value="{{ $equipo->id }}" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded">
                                        </td>
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $equipo->id }}</th>
                                        <td class="px-6 py-4">{{ $equipo->nombre }}</td>
                                        <td class="px-6 py-4">{{ $equipo->deleted_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex gap-3 justify-end">
                                                <button wire:click="restore({{ $equipo->id }})" class="font-medium text-green-600 dark:text-green-500 hover:underline inline-flex items-center gap-1">
                                                    <x-spinner size="xs" color="current" wire:loading wire:target="restore({{ $equipo->id }})" style="display: none;" />
                                                    <span>Restaurar</span>
                                                </button>
                                                <button wire:click="forceDelete({{ $equipo->id }})" class="font-medium text-red-600 dark:text-red-500 hover:underline inline-flex items-center gap-1">
                                                    <x-spinner size="xs" color="current" wire:loading wire:target="forceDelete({{ $equipo->id }})" style="display: none;" />
                                                    <span>Eliminar Definitivamente</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="5" class="px-6 py-4 text-center">No hay equipos en la papelera.</td>
                                    </tr>
                                @endforelse
                            </x-slot>
                        </x-data-table>
                    @else
                        {{-- Vista de equipos activos --}}
                        <x-data-table>
                            <x-slot name="thead">
                                <tr>
                                    <th class="p-4">
                                        <input wire:model.live="selectAll" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    </th>
                                    <x-sortable-header field="id" :currentField="$sortField" :direction="$sortDirection->value">ID</x-sortable-header>
                                    <x-sortable-header field="nombre" :currentField="$sortField" :direction="$sortDirection->value">Nombre</x-sortable-header>
                                    <th class="px-6 py-3 text-right"><span class="sr-only">Acciones</span></th>
                                </tr>
                            </x-slot>
                            <x-slot name="tbody">
                                {{-- Equipo recién creado resaltado --}}
                                @if ($equipoRecienCreado)
                                    <tr class="bg-green-100 dark:bg-green-900 border-b border-green-200 dark:border-green-800">
                                        <td class="w-4 p-4"></td>
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $equipoRecienCreado->id }}</th>
                                        <td class="px-6 py-4">{{ $equipoRecienCreado->nombre }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex gap-3 justify-end">
                                                <button wire:click="edit({{ $equipoRecienCreado->id }})" class="font-medium text-blue-600 dark:text-blue-500 hover:underline inline-flex items-center gap-1">
                                                    <x-spinner size="xs" color="current" wire:loading wire:target="edit({{ $equipoRecienCreado->id }})" style="display: none;" />
                                                    <span>Editar</span>
                                                </button>
                                                <button wire:click="delete({{ $equipoRecienCreado->id }})" class="font-medium text-red-600 dark:text-red-500 hover:underline inline-flex items-center gap-1">
                                                    <x-spinner size="xs" color="current" wire:loading wire:target="delete({{ $equipoRecienCreado->id }})" style="display: none;" />
                                                    <span>Eliminar</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                
                                @forelse ($this->items as $equipo)
                                    @if (!$equipoRecienCreado || $equipoRecienCreado->id !== $equipo->id)
                                        <tr wire:key="equipo-{{ $equipo->id }}" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="w-4 p-4">
                                                <input wire:model.live="selectedItems" value="{{ $equipo->id }}" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded">
                                            </td>
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $equipo->id }}</th>
                                            <td class="px-6 py-4">{{ $equipo->nombre }}</td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex gap-3 justify-end">
                                                    <button wire:click="edit({{ $equipo->id }})" class="font-medium text-blue-600 dark:text-blue-500 hover:underline inline-flex items-center gap-1">
                                                        <x-spinner size="xs" color="current" wire:loading wire:target="edit({{ $equipo->id }})" style="display: none;" />
                                                        <span>Editar</span>
                                                    </button>
                                                    <button wire:click="delete({{ $equipo->id }})" class="font-medium text-red-600 dark:text-red-500 hover:underline inline-flex items-center gap-1">
                                                        <x-spinner size="xs" color="current" wire:loading wire:target="delete({{ $equipo->id }})" style="display: none;" />
                                                        <span>Eliminar</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    @if (!$equipoRecienCreado)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <td colspan="4" class="px-6 py-4 text-center">No hay equipos registrados.</td>
                                        </tr>
                                    @endif
                                @endforelse
                            </x-slot>
                        </x-data-table>
                    @endif

                    {{-- Paginación --}}
                    <div class="mt-4">
                        {{ $this->items->links() }}
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODALES --}}
    
    {{-- Modal de Formulario (Crear/Editar) --}}
    <x-form-modal 
        :show="$showFormModal"
        cancelAction="closeFormModal"
        :title="$form->model?->exists ? 'Editar Equipo' : 'Crear Nuevo Equipo'"
        :submitText="$form->model?->exists ? 'Guardar Cambios' : 'Crear Equipo'"
    >
        <div>
            <x-input-label for="nombre" value="Nombre del Equipo" />
            <x-text-input 
                wire:model="form.nombre" 
                id="nombre" 
                class="mt-1 block w-full" 
                type="text"
                placeholder="Ej: Mancuernas, Barra Olímpica..." />
            @error('form.nombre')
                <x-input-error :messages="$message" class="mt-2" />
            @enderror
        </div>
    </x-form-modal>

    {{-- Modal de Confirmación: Eliminar Individual --}}
    <x-confirmation-modal :show="$deletingId !== null" entangleProperty="deletingId">
        <x-slot name="title">Eliminar Equipo</x-slot>
        <x-slot name="content">
            ¿Estás seguro de que deseas eliminar este equipo? Se moverá a la papelera y podrás restaurarlo más tarde.
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('deletingId', null)">Cancelar</x-secondary-button>
            <x-danger-button class="ml-3" wire:click="performDelete" loadingTarget="performDelete">Eliminar</x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    {{-- Modal de Confirmación: Restaurar Individual --}}
    <x-confirmation-modal :show="$restoringId !== null" entangleProperty="restoringId">
        <x-slot name="title">Restaurar Equipo</x-slot>
        <x-slot name="content">
            ¿Estás seguro de que deseas restaurar este equipo?
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('restoringId', null)">Cancelar</x-secondary-button>
            <x-primary-button class="ml-3" wire:click="performRestore" loadingTarget="performRestore">Restaurar</x-primary-button>
        </x-slot>
    </x-confirmation-modal>

    {{-- Modal de Confirmación: Eliminar Permanentemente Individual --}}
    <x-confirmation-modal :show="$forceDeleteingId !== null" entangleProperty="forceDeleteingId">
        <x-slot name="title">Eliminar Permanentemente</x-slot>
        <x-slot name="content">
            <strong class="text-red-600 dark:text-red-400">¡Esta acción no se puede deshacer!</strong><br>
            ¿Estás seguro de que deseas eliminar este equipo permanentemente?
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('forceDeleteingId', null)">Cancelar</x-secondary-button>
            <x-danger-button class="ml-3" wire:click="performForceDelete" loadingTarget="performForceDelete">Eliminar Permanentemente</x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    {{-- Modal de Confirmación: Eliminar en Lote --}}
    <x-confirmation-modal :show="$confirmingBulkDelete" entangleProperty="confirmingBulkDelete">
        <x-slot name="title">Eliminar Equipos Seleccionados</x-slot>
        <x-slot name="content">
            ¿Estás seguro de que deseas eliminar <strong>{{ $this->selectedCount }}</strong> equipo(s)? Se moverán a la papelera.
            @if($selectingAll)
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Se eliminarán <strong>todos</strong> los equipos que coinciden con los filtros actuales.
                </p>
            @endif
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmingBulkDelete', false)">Cancelar</x-secondary-button>
            <x-danger-button class="ml-3" wire:click="deleteSelected" loadingTarget="deleteSelected">Eliminar Seleccionados</x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    {{-- Modal de Confirmación: Restaurar en Lote --}}
    <x-confirmation-modal :show="$confirmingBulkRestore" entangleProperty="confirmingBulkRestore">
        <x-slot name="title">Restaurar Equipos Seleccionados</x-slot>
        <x-slot name="content">
            ¿Estás seguro de que deseas restaurar <strong>{{ $this->selectedCount }}</strong> equipo(s)?
            @if($selectingAll)
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Se restaurarán <strong>todos</strong> los equipos que coinciden con los filtros actuales.
                </p>
            @endif
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmingBulkRestore', false)">Cancelar</x-secondary-button>
            <x-primary-button class="ml-3" wire:click="restoreSelected" loadingTarget="restoreSelected">Restaurar Seleccionados</x-primary-button>
        </x-slot>
    </x-confirmation-modal>

    {{-- Modal de Confirmación: Eliminar Permanentemente en Lote --}}
    <x-confirmation-modal :show="$confirmingBulkForceDelete" entangleProperty="confirmingBulkForceDelete">
        <x-slot name="title">Eliminar Permanentemente Equipos Seleccionados</x-slot>
        <x-slot name="content">
            <strong class="text-red-600 dark:text-red-400">¡Esta acción no se puede deshacer!</strong><br>
            ¿Estás seguro de que deseas eliminar permanentemente <strong>{{ $this->selectedCount }}</strong> equipo(s)?
            @if($selectingAll)
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Se eliminarán permanentemente <strong>todos</strong> los equipos que coinciden con los filtros actuales.
                </p>
            @endif
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmingBulkForceDelete', false)">Cancelar</x-secondary-button>
            <x-danger-button class="ml-3" wire:click="forceDeleteSelected" loadingTarget="forceDeleteSelected">Eliminar Permanentemente</x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    {{-- Loading overlay para operaciones largas --}}
    <x-loading-overlay 
        target="deleteSelected,restoreSelected,forceDeleteSelected,performDelete,performRestore,performForceDelete"
        message="Procesando operación..."
    />
</div>