    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $showingTrash ? 'Papelera de Usuarios' : 'Gestión de Usuarios' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex gap-3">
                            <x-secondary-button wire:click="toggleTrash" loadingTarget="toggleTrash">
                                {{ $showingTrash ? 'Ver Activos' : 'Ver Papelera' }}
                            </x-secondary-button>
                            
                            @if(!$showingTrash)
                            <x-primary-button wire:click="create">
                                Nuevo Usuario
                            </x-primary-button>
                            @endif
                        </div>
                    </div>

    <!-- TABS DE FILTRADO POR ROL (Solo para Admins) -->
    @if(!auth()->user()->esEntrenador())
    <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" role="tablist">
            <li class="mr-2" role="presentation">
                <button wire:click="setFiltroRol(null)" 
                        class="inline-block p-4 border-b-2 rounded-t-lg {{ is_null($filtroRol) ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    Todos
                </button>
            </li>
            <!-- Administradores ocultos por seguridad -->
            <li class="mr-2" role="presentation">
                <button wire:click="setFiltroRol(2)" 
                        class="inline-block p-4 border-b-2 rounded-t-lg {{ $filtroRol === 2 ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    Entrenadores
                </button>
            </li>
            <li class="mr-2" role="presentation">
                <button wire:click="setFiltroRol(3)" 
                        class="inline-block p-4 border-b-2 rounded-t-lg {{ $filtroRol === 3 ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    Atletas
                </button>
            </li>
        </ul>
    </div>
    @endif

    <!-- TABLA DE USUARIOS -->
    <x-data-table :items="$this->items">
        <x-slot name="thead">
            <tr>
                <x-sortable-header field="id" :currentField="$sortField" :direction="$sortDirection->value">ID</x-sortable-header>
                <x-sortable-header field="usuario" :currentField="$sortField" :direction="$sortDirection->value">Usuario</x-sortable-header>
                <x-sortable-header field="nombre_1" :currentField="$sortField" :direction="$sortDirection->value">Nombre Completo</x-sortable-header>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Rol
                </th>
                @if($filtroRol === 3 || is_null($filtroRol))
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Entrenador Asignado
                </th>
                @endif
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Estado
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Acciones
                </th>
            </tr>
        </x-slot>

        <x-slot name="tbody">
            @forelse($this->items as $user)
                <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $user->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->usuario }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $user->nombre_1 }} {{ $user->apellido_1 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $user->tipo_usuario_id === 1 ? 'bg-red-100 text-red-800' : 
                               ($user->tipo_usuario_id === 2 ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                            {{ $user->tipoUsuario->rol ?? 'N/A' }}
                        </span>
                    </td>
                    @if($filtroRol === 3 || is_null($filtroRol))
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        @if($user->tipo_usuario_id === 3 && $user->entrenador)
                            {{ $user->entrenador->nombre_1 }} {{ $user->entrenador->apellido_1 }}
                        @elseif($user->tipo_usuario_id === 3)
                            <span class="text-yellow-500 italic">Sin asignar</span>
                        @else
                            -
                        @endif
                    </td>
                    @endif
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        @if($user->estado)
                            <span class="text-green-600 font-bold">Activo</span>
                        @else
                            <span class="text-red-600 font-bold">Inactivo</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        @if($showingTrash)
                            <button wire:click="restore({{ $user->id }})" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 mr-3">Restaurar</button>
                            <button wire:click="forceDelete({{ $user->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Eliminar</button>
                        @else
                            <button wire:click="edit({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">Editar</button>
                            <button wire:click="delete({{ $user->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Eliminar</button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                        No se encontraron usuarios.
                    </td>
                </tr>
            @endforelse
        </x-slot>
    </x-data-table>

    <!-- MODAL DE CREACIÓN / EDICIÓN -->
    <x-form-modal 
        wire:model="showFormModal" 
        :show="$showFormModal" 
        maxWidth="4xl"
        cancelAction="closeFormModal"
        :title="$isEditing ? 'Editar Usuario' : 'Crear Nuevo Usuario'"
        :submitText="$isEditing ? 'Actualizar Usuario' : 'Crear Usuario'"
    >
        {{-- Content --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- COLUMNA 1: DATOS DE CUENTA -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b pb-2">Datos de Cuenta</h3>
                    
                    <div>
                        <x-input-label for="usuario" :value="__('Usuario (Login)')" />
                        <x-text-input wire:model="usuario" id="usuario" class="block mt-1 w-full" type="text" required />
                        @error('usuario') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="correo" :value="__('Correo Electrónico')" />
                        <x-text-input wire:model="correo" id="correo" class="block mt-1 w-full" type="email" required />
                        @error('correo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="tipo_usuario_id" :value="__('Rol de Usuario')" />
                        
                        @if(auth()->user()->esEntrenador())
                            <!-- Si es Entrenador, el rol es fijo: Atleta -->
                            <div class="block mt-1 w-full p-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300">
                                Atleta
                            </div>
                        @else
                            <select wire:model.live="tipo_usuario_id" id="tipo_usuario_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Seleccione un Rol</option>
                                @foreach($tipos_usuario_list as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->rol }}</option>
                                @endforeach
                            </select>
                        @endif
                        @error('tipo_usuario_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- CAMPO CONDICIONAL: ENTRENADOR (SOLO SI ES ATLETA Y NO ES ENTRENADOR QUIEN CREA) -->
                    @if($tipo_usuario_id == 3 && !auth()->user()->esEntrenador())
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                        <x-input-label for="entrenador_id" :value="__('Asignar Entrenador')" />
                        <select wire:model="entrenador_id" id="entrenador_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">-- Sin Entrenador --</option>
                            @foreach($entrenadores_list as $entrenador)
                                <option value="{{ $entrenador->id }}">{{ $entrenador->nombre_1 }} {{ $entrenador->apellido_1 }} ({{ $entrenador->usuario }})</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Seleccione el entrenador responsable de este atleta.</p>
                        @error('entrenador_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    @endif
                </div>

                <!-- COLUMNA 2: DATOS PERSONALES -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b pb-2">Datos Personales</h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="nombre_1" :value="__('Primer Nombre')" />
                            <x-text-input wire:model="nombre_1" id="nombre_1" class="block mt-1 w-full" type="text" required />
                            @error('nombre_1') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <x-input-label for="nombre_2" :value="__('Segundo Nombre')" />
                            <x-text-input wire:model="nombre_2" id="nombre_2" class="block mt-1 w-full" type="text" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="apellido_1" :value="__('Primer Apellido')" />
                            <x-text-input wire:model="apellido_1" id="apellido_1" class="block mt-1 w-full" type="text" required />
                            @error('apellido_1') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <x-input-label for="apellido_2" :value="__('Segundo Apellido')" />
                            <x-text-input wire:model="apellido_2" id="apellido_2" class="block mt-1 w-full" type="text" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="telefono" :value="__('Teléfono')" />
                        <x-text-input wire:model="telefono" id="telefono" class="block mt-1 w-full" type="text" required />
                        @error('telefono') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="fecha_nacimiento" :value="__('Fecha de Nacimiento')" />
                        <x-text-input wire:model="fecha_nacimiento" id="fecha_nacimiento" class="block mt-1 w-full" type="date" required />
                        @error('fecha_nacimiento') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            
            @if(!$isEditing)
            <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md">
                <p class="text-sm text-yellow-800 dark:text-yellow-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <strong>Nota:</strong> Se asignará la contraseña temporal <code>password</code> al crear el usuario.
                </p>
            </div>
            @endif
    </x-form-modal>

    {{-- Modales de Confirmación --}}
    <x-confirmation-modal :show="$deletingId !== null" entangleProperty="deletingId">
        <x-slot name="title">Eliminar Usuario</x-slot>
        <x-slot name="content">¿Estás seguro? Se moverá a la papelera.</x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('deletingId', null)">Cancelar</x-secondary-button>
            <x-danger-button class="ml-3" wire:click="performDelete">Eliminar</x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <x-confirmation-modal :show="$restoringId !== null" entangleProperty="restoringId">
        <x-slot name="title">Restaurar Usuario</x-slot>
        <x-slot name="content">¿Estás seguro de querer restaurar este usuario?</x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('restoringId', null)">Cancelar</x-secondary-button>
            <x-primary-button class="ml-3" wire:click="performRestore">Restaurar</x-primary-button>
        </x-slot>
    </x-confirmation-modal>

    <x-confirmation-modal :show="$forceDeleteingId !== null" entangleProperty="forceDeleteingId">
        <x-slot name="title">Eliminar Permanentemente</x-slot>
        <x-slot name="content">¿Estás seguro? Esta acción es irreversible y eliminará al usuario definitivamente.</x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('forceDeleteingId', null)">Cancelar</x-secondary-button>
            <x-danger-button class="ml-3" wire:click="performForceDelete">Eliminar Permanentemente</x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <!-- MODAL DE CONFIRMACIÓN DE ELIMINACIÓN -->
    <x-confirmation-modal wire:model="showDeleteModal">
        <x-slot name="title">
            {{ __('Eliminar Usuario') }}
        </x-slot>

        <x-slot name="content">
            {{ __('¿Estás seguro de que deseas eliminar este usuario? Esta acción moverá el usuario a la papelera.') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showDeleteModal', false)" wire:loading.attr="disabled">
                {{ __('Cancelar') }}
            </x-secondary-button>

            <x-danger-button class="ml-3" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Eliminar') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>
