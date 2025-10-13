<div>
    {{-- BLOQUE 1: TÍTULO DE LA PÁGINA --}}
    {{-- Este slot se inyecta en el layout principal para establecer el título de la sección. --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{-- El título cambia dinámicamente según si estamos en la papelera o no. --}}
            {{ $showingTrash ? 'Papelera de Equipos' : 'Gestión de Equipos' }}
        </h2>
    </x-slot>

    {{-- BLOQUE 2: CONTENEDOR PRINCIPAL --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- BLOQUE 3: BARRA DE ACCIONES Y BÚSQUEDA --}}
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-4 w-1/3">
                            {{-- Campo de búsqueda vinculado a la propiedad $search con actualización en tiempo real (.live) --}}
                            <x-text-input 
                                wire:model.live="search"
                                class="block w-full" 
                                type="text" 
                                placeholder="Buscar equipo..." />

                            {{-- Dropdown de Acciones en Lote para la lista de ACTIVOS --}}
                            {{-- Solo aparece si hay equipos seleccionados Y no estamos en la papelera. --}}
                            @if(count($selectedEquipos) > 0 && !$showingTrash)
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                                    <span>Acciones ({{ count($selectedEquipos) }})</span>
                                    <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </button>
                                <div x-show="open" @click.away="open = false" class="origin-top-left absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 z-10">
                                    <div class="py-1" role="menu" aria-orientation="vertical">
                                        <a href="#" wire:click.prevent="confirmDeleteSelected" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600" role="menuitem">Eliminar Seleccionados</a>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            {{-- Dropdown de Acciones en Lote para la PAPELERA --}}
                            {{-- Solo aparece si hay equipos seleccionados Y estamos en la papelera. --}}
                            @if(count($selectedEquipos) > 0 && $showingTrash)
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                                    <span>Acciones ({{ count($selectedEquipos) }})</span>
                                    <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </button>
                                <div x-show="open" @click.away="open = false" class="origin-top-left absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 z-10">
                                    <div class="py-1" role="menu" aria-orientation="vertical">
                                        <a href="#" wire:click.prevent="confirmRestoreSelected" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600" role="menuitem">Restaurar Seleccionados</a>
                                        <a href="#" wire:click.prevent="confirmForceDeleteSelected" class="block px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-600" role="menuitem">Eliminar Permanentemente</a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        {{-- Botones de acciones principales --}}
                        <div class="flex gap-4">
                            <button wire:click="toggleTrash" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-sm">{{ $showingTrash ? 'Ver Activos' : 'Ver Papelera' }}</button>
                            <button wire:click="crearEquipo" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Crear Equipo</button>
                        </div>
                    </div>

                    {{-- BLOQUE 4: CONTENIDO DINÁMICO (TABLAS) --}}
                    {{-- Este @if principal cambia todo el contenido de la tabla dependiendo de la vista. --}}
                    @if ($showingTrash)
                        {{-- VISTA DE LA PAPELERA --}}
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="p-4"><input wire:model.live="selectAll" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"></th>
                                        <th scope="col" class="px-6 py-3"><button wire:click="sortBy('id')" class="flex items-center gap-2">ID</button></th>
                                        <th scope="col" class="px-6 py-3"><button wire:click="sortBy('nombre')" class="flex items-center gap-2">Nombre</button></th>
                                        <th scope="col" class="px-6 py-3"><button wire:click="sortBy('deleted_at')" class="flex items-center gap-2">Fecha de Eliminación</button></th>
                                        <th scope="col" class="px-6 py-3 text-right"><span class="sr-only">Acciones</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($equipos as $equipo)
                                    <tr wire:key="papelera-{{ $equipo->id }}" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="w-4 p-4"><input wire:model.live="selectedEquipos" value="{{ $equipo->id }}" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded"></td>
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $equipo->id }}</th>
                                        <td class="px-6 py-4">{{ $equipo->nombre }}</td>
                                        <td class="px-6 py-4">{{ $equipo->deleted_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex gap-4 justify-end">
                                                <button wire:click="confirmarRestauracion({{ $equipo->id }})" class="font-medium text-green-600 dark:text-green-500 hover:underline">Restaurar</button>
                                                <button wire:click="confirmarBorradoForzado({{ $equipo->id }})" class="font-medium text-red-600 dark:text-red-500 hover:underline">Eliminar Definitivamente</button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="5" class="px-6 py-4 text-center">No hay equipos en la papelera.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        {{-- VISTA DE EQUIPOS ACTIVOS --}}
                        <div class="relative overflow-x-auto">
                           <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="p-4"><input wire:model.live="selectAll" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"></th>
                                        <th scope="col" class="px-6 py-3"><button wire:click="sortBy('id')" class="flex items-center gap-2">ID</button></th>
                                        <th scope="col" class="px-6 py-3"><button wire:click="sortBy('nombre')" class="flex items-center gap-2">Nombre</button></th>
                                        <th scope="col" class="px-6 py-3"><span class="sr-only">Acciones</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Fila temporal para el equipo recién creado, con un fondo verde para resaltarlo --}}
                                    @if ($equipoRecienCreado)
                                    <tr class="bg-green-100 dark:bg-green-900 border-b border-green-200 dark:border-green-800">
                                        <td class="w-4 p-4"></td> {{-- Celda vacía para alinear con checkboxes --}}
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $equipoRecienCreado->id }}</th>
                                        <td class="px-6 py-4">{{ $equipoRecienCreado->nombre }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex gap-4 justify-end">
                                                <button wire:click="editarEquipo({{ $equipoRecienCreado->id }})" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Editar</button>
                                                <button wire:click="confirmarEliminacion({{ $equipoRecienCreado->id }})" class="font-medium text-red-600 dark:text-red-500 hover:underline">Eliminar</button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                    @forelse ($equipos as $equipo)
                                        {{-- Se excluye el recién creado para no duplicarlo en la lista paginada --}}
                                        @if (!$equipoRecienCreado || $equipoRecienCreado->id !== $equipo->id)
                                        <tr wire:key="equipo-{{ $equipo->id }}" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="w-4 p-4"><input wire:model.live="selectedEquipos" value="{{ $equipo->id }}" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded"></td>
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $equipo->id }}</th>
                                            <td class="px-6 py-4">{{ $equipo->nombre }}</td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex gap-4 justify-end">
                                                    <button wire:click="editarEquipo({{ $equipo->id }})" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Editar</button>
                                                    <button wire:click="confirmarEliminacion({{ $equipo->id }})" class="font-medium text-red-600 dark:text-red-500 hover:underline">Eliminar</button>
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
                                </tbody>
                            </table>
                        </div>
                    @endif

                    {{-- BLOQUE 5: SECCIÓN DE MODALES GLOBALES --}}
                    {{-- Todos los modales se declaran aquí, una sola vez, para mayor eficiencia. --}}
                    {{-- Su visibilidad se controla con las propiedades del componente PHP. --}}

                    {{-- Modal de Creación --}}
                    @if ($showingCrearModal)
                        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto"><div wire:click="cancelarCreacion" class="fixed inset-0 bg-gray-500 bg-opacity-75"></div><div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-lg"><h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Crear Nuevo Equipo</h3><div class="mt-4"><label for="nombre-nuevo-equipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label><x-text-input wire:model="nombreNuevoEquipo" id="nombre-nuevo-equipo" class="mt-1 block w-full" type="text" /><@error('nombreNuevoEquipo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror</div><div class="mt-6 flex justify-end gap-4"><x-secondary-button wire:click="cancelarCreacion">Cancelar</x-secondary-button><x-primary-button wire:click="storeEquipo">Crear Equipo</x-primary-button></div></div></div>
                    @endif
                    
                    {{-- Modal de Edición --}}
                    @if ($equipoParaEditar)
                        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto"><div wire:click="cancelarEdicion" class="fixed inset-0 bg-gray-500 bg-opacity-75"></div><div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-lg"><h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Editar Equipo</h3><div class="mt-4"><label for="nombre-equipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label><x-text-input wire:model="nombreEquipoEnEdicion" id="nombre-equipo" class="mt-1 block w-full" type="text" /><@error('nombreEquipoEnEdicion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror</div><div class="mt-6 flex justify-end gap-4"><x-secondary-button wire:click="cancelarEdicion">Cancelar</x-secondary-button><x-primary-button wire:click="updateEquipo">Guardar Cambios</x-primary-button></div></div></div>
                    @endif

                    {{-- Modal de Eliminación Individual (Soft Delete) --}}
                    @if ($equipoParaEliminarId)
                        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto"><div wire:click="cancelarEliminacion" class="fixed inset-0 bg-gray-500 bg-opacity-75"></div><div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6"><h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Confirmar Eliminación</h3><div class="mt-2"><p class="text-sm text-gray-500 dark:text-gray-400">¿Estás seguro? Esta acción lo enviará a la papelera.</p></div><div class="mt-6 flex justify-end gap-4"><x-secondary-button wire:click="cancelarEliminacion">Cancelar</x-secondary-button><x-danger-button wire:click="deleteEquipo">Sí, Eliminar</x-danger-button></div></div></div>
                    @endif

                    {{-- Modal de Restauración Individual --}}
                    @if ($equipoParaRestaurarId)
                        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto"><div wire:click="cancelarRestauracion" class="fixed inset-0 bg-gray-500 bg-opacity-75"></div><div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6"><h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Confirmar Restauración</h3><div class="mt-2"><p class="text-sm text-gray-500 dark:text-gray-400">¿Estás seguro de que deseas restaurar este equipo?</p></div><div class="mt-6 flex justify-end gap-4"><x-secondary-button wire:click="cancelarRestauracion">Cancelar</x-secondary-button><x-primary-button wire:click="restoreEquipo">Sí, Restaurar</x-primary-button></div></div></div>
                    @endif

                    {{-- Modal de Borrado Forzado Individual --}}
                    @if ($equipoParaBorradoForzadoId)
                        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto"><div wire:click="cancelarBorradoForzado" class="fixed inset-0 bg-gray-500 bg-opacity-75"></div><div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6"><h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Confirmar Eliminación Permanente</h3><div class="mt-2"><p class="text-sm text-gray-500 dark:text-gray-400">¿Estás seguro? <strong class="text-red-500">Esta acción no se puede deshacer.</strong></p></div><div class="mt-6 flex justify-end gap-4"><x-secondary-button wire:click="cancelarBorradoForzado">Cancelar</x-secondary-button><x-danger-button wire:click="forceDeleteEquipo">Sí, Eliminar Permanentemente</x-danger-button></div></div></div>
                    @endif

                    {{-- Modal de Borrado en Lote (Activos) --}}
                    @if ($confirmingBulkDelete)
                        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto"><div wire:click="$set('confirmingBulkDelete', false)" class="fixed inset-0 bg-gray-500 bg-opacity-75"></div><div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6"><h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Confirmar Eliminación en Lote</h3><div class="mt-2"><p class="text-sm text-gray-500 dark:text-gray-400">¿Estás seguro de que deseas eliminar los <strong>{{ count($selectedEquipos) }}</strong> equipos seleccionados?</p></div><div class="mt-6 flex justify-end gap-4"><x-secondary-button wire:click="$set('confirmingBulkDelete', false)">Cancelar</x-secondary-button><x-danger-button wire:click="deleteSelected">Sí, Eliminar Seleccionados</x-danger-button></div></div></div>
                    @endif

                    {{-- Modal de Restauración en Lote (Papelera) --}}
                    @if ($confirmingBulkRestore)
                        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto"><div wire:click="$set('confirmingBulkRestore', false)" class="fixed inset-0 bg-gray-500 bg-opacity-75"></div><div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6"><h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Confirmar Restauración en Lote</h3><div class="mt-2"><p class="text-sm text-gray-500 dark:text-gray-400">¿Estás seguro de que deseas restaurar los <strong>{{ count($selectedEquipos) }}</strong> equipos seleccionados?</p></div><div class="mt-6 flex justify-end gap-4"><x-secondary-button wire:click="$set('confirmingBulkRestore', false)">Cancelar</x-secondary-button><x-primary-button wire:click="restoreSelected">Sí, Restaurar Seleccionados</x-primary-button></div></div></div>
                    @endif

                    {{-- Modal de Borrado Forzado en Lote (Papelera) --}}
                    @if ($confirmingBulkForceDelete)
                        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto"><div wire:click="$set('confirmingBulkForceDelete', false)" class="fixed inset-0 bg-gray-500 bg-opacity-75"></div><div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6"><h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Confirmar Eliminación Permanente en Lote</h3><div class="mt-2"><p class="text-sm text-gray-500 dark:text-gray-400">¿Estás seguro? Los <strong>{{ count($selectedEquipos) }}</strong> equipos seleccionados se eliminarán permanentemente. <strong class="text-red-500">Esta acción no se puede deshacer.</strong></p></div><div class="mt-6 flex justify-end gap-4"><x-secondary-button wire:click="$set('confirmingBulkForceDelete', false)">Cancelar</x-secondary-button><x-danger-button wire:click="forceDeleteSelected">Sí, Eliminar Permanentemente</x-danger-button></div></div></div>
                    @endif

                    {{-- BLOQUE 6: PAGINACIÓN --}}
                    <div class="mt-4">
                        {{ $equipos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>