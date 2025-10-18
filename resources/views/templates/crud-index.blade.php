{{--
    PLANTILLA OPTIMIZADA PARA VISTAS CRUD
    
    Esta plantilla proporciona una estructura completa y optimizada para vistas CRUD.
    Copia este archivo y personaliza las secciones marcadas con {{-- PERSONALIZAR --}}
    
    REDUCCIÓN: De ~360 líneas a ~120 líneas (67% menos código)
    
    USO:
    1. Copia este archivo a tu vista (ej: livewire/admin/gestionar-ejercicios.blade.php)
    2. Busca todos los comentarios "PERSONALIZAR" y ajusta según tu modelo
    3. ¡Listo!
--}}

<div>
    {{-- PERSONALIZAR: Título de la página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $showingTrash ? 'Papelera de Equipos' : 'Gestión de Equipos' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Toolbar: Búsqueda y botones --}}
                    <x-crud-toolbar
                        searchPlaceholder="Buscar equipo..."  {{-- PERSONALIZAR --}}
                        createButtonText="Crear Equipo"        {{-- PERSONALIZAR --}}
                        :showingTrash="$showingTrash"
                    />

                    {{-- Banners de selección masiva --}}
                    <x-selection-banners
                        entityName="equipo"                   {{-- PERSONALIZAR --}}
                        entityNamePlural="equipos"            {{-- PERSONALIZAR --}}
                    />

                    {{-- Loading state --}}
                    <x-loading-state 
                        target="search,toggleTrash,sortBy,gotoPage,previousPage,nextPage" 
                        message="Cargando equipos..."         {{-- PERSONALIZAR --}}
                        class="my-4"
                    />

                    {{-- Tabla --}}
                    <div wire:loading.remove wire:target="search,toggleTrash,sortBy,gotoPage,previousPage,nextPage">
                        @if ($showingTrash)
                            {{-- Vista de la papelera --}}
                            <x-data-table>
                                <x-slot name="thead">
                                    <tr>
                                        <th class="p-4">
                                            <input wire:model.live="selectAll" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        </th>
                                        {{-- PERSONALIZAR: Columnas de la tabla --}}
                                        <x-sortable-header field="id" :currentField="$sortField" :direction="$sortDirection->value">ID</x-sortable-header>
                                        <x-sortable-header field="nombre" :currentField="$sortField" :direction="$sortDirection->value">Nombre</x-sortable-header>
                                        <x-sortable-header field="deleted_at" :currentField="$sortField" :direction="$sortDirection->value">Fecha Eliminación</x-sortable-header>
                                        <th class="px-6 py-3 text-right"><span class="sr-only">Acciones</span></th>
                                    </tr>
                                </x-slot>
                                <x-slot name="tbody">
                                    @forelse ($this->items as $item)  {{-- Siempre usar $this->items --}}
                                        <tr wire:key="trash-{{ $item->id }}" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="w-4 p-4">
                                                <input wire:model.live="selectedItems" value="{{ $item->id }}" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded">
                                            </td>
                                            {{-- PERSONALIZAR: Datos de la fila --}}
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $item->id }}</th>
                                            <td class="px-6 py-4">{{ $item->nombre }}</td>
                                            <td class="px-6 py-4">{{ $item->deleted_at->format('d/m/Y H:i') }}</td>
                                            {{-- Acciones estándar de papelera --}}
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex gap-3 justify-end">
                                                    <button wire:click="restore({{ $item->id }})" class="font-medium text-green-600 dark:text-green-500 hover:underline inline-flex items-center gap-1">
                                                        <x-spinner size="xs" color="current" wire:loading wire:target="restore({{ $item->id }})" style="display: none;" />
                                                        <span>Restaurar</span>
                                                    </button>
                                                    <button wire:click="forceDelete({{ $item->id }})" class="font-medium text-red-600 dark:text-red-500 hover:underline inline-flex items-center gap-1">
                                                        <x-spinner size="xs" color="current" wire:loading wire:target="forceDelete({{ $item->id }})" style="display: none;" />
                                                        <span>Eliminar Definitivamente</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <td colspan="5" class="px-6 py-4 text-center">No hay equipos en la papelera.</td>  {{-- PERSONALIZAR: colspan según número de columnas --}}
                                        </tr>
                                    @endforelse
                                </x-slot>
                            </x-data-table>
                        @else
                            {{-- Vista de items activos --}}
                            <x-data-table>
                                <x-slot name="thead">
                                    <tr>
                                        <th class="p-4">
                                            <input wire:model.live="selectAll" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        </th>
                                        {{-- PERSONALIZAR: Columnas de la tabla --}}
                                        <x-sortable-header field="id" :currentField="$sortField" :direction="$sortDirection->value">ID</x-sortable-header>
                                        <x-sortable-header field="nombre" :currentField="$sortField" :direction="$sortDirection->value">Nombre</x-sortable-header>
                                        <th class="px-6 py-3 text-right"><span class="sr-only">Acciones</span></th>
                                    </tr>
                                </x-slot>
                                <x-slot name="tbody">
                                    {{-- Item recién creado resaltado (OPCIONAL: eliminar si no necesitas esta funcionalidad) --}}
                                    @if ($equipoRecienCreado)  {{-- PERSONALIZAR: cambiar 'equipoRecienCreado' por tu propiedad --}}
                                        <x-recently-created-row :item="$equipoRecienCreado">
                                            {{-- PERSONALIZAR: Columnas del item --}}
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $equipoRecienCreado->id }}</th>
                                            <td class="px-6 py-4">{{ $equipoRecienCreado->nombre }}</td>
                                        </x-recently-created-row>
                                    @endif
                                    
                                    @forelse ($this->items as $item)
                                        @if (!isset($equipoRecienCreado) || $equipoRecienCreado?->id !== $item->id)  {{-- PERSONALIZAR: cambiar 'equipoRecienCreado' --}}
                                            <tr wire:key="item-{{ $item->id }}" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                <td class="w-4 p-4">
                                                    <input wire:model.live="selectedItems" value="{{ $item->id }}" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded">
                                                </td>
                                                {{-- PERSONALIZAR: Datos de la fila --}}
                                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $item->id }}</th>
                                                <td class="px-6 py-4">{{ $item->nombre }}</td>
                                                {{-- Acciones estándar --}}
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
                                            </tr>
                                        @endif
                                    @empty
                                        @if (!isset($equipoRecienCreado) || !$equipoRecienCreado)  {{-- PERSONALIZAR --}}
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                <td colspan="4" class="px-6 py-4 text-center">No hay equipos registrados.</td>  {{-- PERSONALIZAR: mensaje y colspan --}}
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

    {{-- PERSONALIZAR: Modales (ver archivo original para ejemplos completos) --}}
    {{-- Aquí van los modales de formulario y confirmaciones --}}
    {{-- Para reducir aún más, considera crear un componente <x-crud-modals> --}}
</div>
