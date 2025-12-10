<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $showingTrash ? 'Papelera de Rutinas' : 'Gestión de Rutinas' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Barra de acciones --}}
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-4 w-1/2">
                            {{-- Filtro por Atleta --}}
                            <select wire:model.live="selectedAthlete" class="block w-1/3 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Todos los Atletas</option>
                                @foreach($atletas_list as $atleta)
                                    <option value="{{ $atleta->id }}">{{ $atleta->nombre_1 }} {{ $atleta->apellido_1 }}</option>
                                @endforeach
                            </select>

                            {{-- Buscador --}}
                            <x-text-input wire:model.live="search" class="block w-2/3" type="text" placeholder="Buscar rutina..." />
                        </div>
                        
                        <div class="flex gap-3">
                            <x-secondary-button wire:click="toggleTrash">
                                {{ $showingTrash ? 'Ver Activas' : 'Ver Papelera' }}
                            </x-secondary-button>
                            @if(!$showingTrash)
                                <x-primary-button wire:click="create">
                                    Nueva Rutina
                                </x-primary-button>
                            @endif
                        </div>
                    </div>

                    {{-- Tabla --}}
                    <x-data-table>
                        <x-slot name="thead">
                            <tr>
                                <x-sortable-header field="id" :currentField="$sortField" :direction="$sortDirection->value">ID</x-sortable-header>
                                <x-sortable-header field="nombre" :currentField="$sortField" :direction="$sortDirection->value">Nombre</x-sortable-header>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Atleta</th>
                                <th class="px-6 py-3 text-right">Acciones</th>
                            </tr>
                        </x-slot>
                        <x-slot name="tbody">
                            @forelse ($this->items as $rutina)
                                <tr wire:key="rutina-{{ $rutina->id }}" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4">{{ $rutina->id }}</td>
                                    <td class="px-6 py-4 font-medium">{{ $rutina->nombre }}</td>
                                    <td class="px-6 py-4">
                                        {{ $rutina->atleta?->nombre_1 ?? 'Sin Asignar' }} {{ $rutina->atleta?->apellido_1 }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex gap-3 justify-end items-center">
                                            @if(!$showingTrash)
                                                {{-- Botón Activar/Desactivar --}}
                                                <button 
                                                    wire:click="toggleActive({{ $rutina->id }})" 
                                                    class="px-2 py-1 text-xs font-semibold rounded-full transition-colors {{ $rutina->estado ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 hover:bg-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200' }}"
                                                    title="{{ $rutina->estado ? 'Desactivar Rutina' : 'Activar Rutina' }}"
                                                >
                                                    {{ $rutina->estado ? 'ACTIVA' : 'INACTIVA' }}
                                                </button>

                                                {{-- Botón Calendario --}}
                                                <a href="{{ route('admin.rutinas.calendario', $rutina->id) }}" class="font-medium text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    Calendario
                                                </a>
                                                
                                                <button wire:click="edit({{ $rutina->id }})" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Editar</button>
                                                <button wire:click="delete({{ $rutina->id }})" class="font-medium text-red-600 dark:text-red-500 hover:underline">Eliminar</button>
                                            @else
                                                <button wire:click="restore({{ $rutina->id }})" class="font-medium text-green-600 dark:text-green-500 hover:underline">Restaurar</button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center dark:text-gray-400">No hay rutinas registradas.</td>
                                </tr>
                            @endforelse
                        </x-slot>
                    </x-data-table>

                    <div class="mt-4">
                        {{ $this->items->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Formulario --}}
    <x-form-modal :show="$showFormModal" cancelAction="closeFormModal" :title="$form->model?->exists ? 'Editar Rutina' : 'Nueva Rutina'">
        <div class="space-y-4">
            <div>
                <x-input-label for="nombre" value="Nombre de la Rutina" />
                <x-text-input wire:model="form.nombre" id="nombre" class="block w-full mt-1" type="text" placeholder="Ej: Mesociclo 1 - Hipertrofia" />
                @error('form.nombre') <x-input-error :messages="$message" class="mt-2" /> @enderror
            </div>

            <div>
                <x-input-label for="atleta_id" value="Atleta Asignado" />
                <select wire:model="form.atleta_id" id="atleta_id" class="block w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="">Seleccione un atleta...</option>
                    @foreach($atletas_list as $atleta)
                        <option value="{{ $atleta->id }}">{{ $atleta->nombre_1 }} {{ $atleta->apellido_1 }} ({{ $atleta->usuario }})</option>
                    @endforeach
                </select>
                @error('form.atleta_id') <x-input-error :messages="$message" class="mt-2" /> @enderror
            </div>

            <div>
                <x-input-label for="descripcion" value="Descripción (Opcional)" />
                <textarea wire:model="form.descripcion" id="descripcion" rows="3" class="block w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></textarea>
                @error('form.descripcion') <x-input-error :messages="$message" class="mt-2" /> @enderror
            </div>
        </div>
    </x-form-modal>
</div>
