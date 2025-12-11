    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $showingTrash ? __('users.trash_title') : __('users.management_title') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('users.title') }}</h2>
                        <div class="flex space-x-2">
                            <x-secondary-button wire:click="toggleTrash" loadingTarget="toggleTrash">
                                {{ $showingTrash ? __('users.filters.all') : __('users.filters.trash') }}
                            </x-secondary-button>

                            
                            @if(!$showingTrash)
                            @can('create', \App\Models\User::class)
                            <x-primary-button wire:click="create" loadingTarget="create">
                                {{ __('users.create_new') }}
                            </x-primary-button>
                            @endcan
                            @endif
                        </div>
                    </div>

                    <div class="relative mb-4">
                        <input wire:model.live.debounce.300ms="search" type="text" 
                            placeholder="{{ __('users.search_placeholder') }}" 
                            class="w-full pl-10 pr-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:border-blue-500">
                        <div class="absolute left-3 top-2.5 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>

    <!-- TABS DE FILTRADO POR ROL (Solo para Admins) -->
    @if(!auth()->user()->esEntrenador())
        <x-filter-pills 
            :options="[
                ['label' => __('users.filters.all'), 'value' => null],
                ['label' => __('users.filters.trainers'), 'value' => \App\Enums\UserRole::Entrenador->value],
                ['label' => __('users.filters.athletes'), 'value' => \App\Enums\UserRole::Atleta->value]
            ]"
            :activeValue="$filtroRol"
            action="setFiltroRol"
        />
    @endif

    <!-- TABLA DE USUARIOS -->
    <x-data-table :items="$this->items">
        <x-slot name="thead">
            <tr>
                <x-sortable-header field="id" :currentField="$sortField" :direction="$sortDirection->value">{{ __('users.table.id') }}</x-sortable-header>
                <x-sortable-header field="usuario" :currentField="$sortField" :direction="$sortDirection->value">{{ __('users.table.user') }}</x-sortable-header>
                <x-sortable-header field="nombre_1" :currentField="$sortField" :direction="$sortDirection->value">{{ __('users.table.name') }}</x-sortable-header>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ __('users.table.role') }}
                </th>
                @if($filtroRol === \App\Enums\UserRole::Atleta->value || is_null($filtroRol))
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ __('users.form.trainer') }}
                </th>
                @endif
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ __('users.table.status') }}
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ __('users.table.actions') }}
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
                    <td class="px-6 py-4 whitespace-nowrap">
                        <x-role-badge :role="$user->tipo_usuario_id" />
                    </td>
                    @if($filtroRol === \App\Enums\UserRole::Atleta->value || is_null($filtroRol))
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        @if($user->tipo_usuario_id === \App\Enums\UserRole::Atleta && $user->entrenador)
                            {{ $user->entrenador->nombre_1 }} {{ $user->entrenador->apellido_1 }}
                        @elseif($user->tipo_usuario_id === \App\Enums\UserRole::Atleta)
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
                            @can('restore', $user)
                            <button wire:click="restore({{ $user->id }})" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 mr-3">Restaurar</button>
                            @endcan
                            @can('forceDelete', $user)
                            <button wire:click="forceDelete({{ $user->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Eliminar</button>
                            @endcan
                        @else
                            <button wire:click="$dispatch('open-audit-history', { modelType: 'App\\Models\\User', modelId: {{ $user->id }} })" class="text-gray-400 hover:text-gray-600 mr-2" title="Ver Historial">
                                <i class="fas fa-history"></i>
                            </button>
                            @if(auth()->user()->esAdmin() && $user->id !== auth()->id())
                            <a href="{{ route('admin.impersonate', $user->id) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 mr-2" title="Iniciar Sesión Como...">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            @endif
                            @can('update', $user)
                            <button wire:click="confirmPasswordReset({{ $user->id }})" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 mr-2" title="Restablecer Contraseña">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                            </button>
                            <button wire:click="edit({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">Editar</button>
                            @endcan
                            @can('delete', $user)
                            <button wire:click="delete({{ $user->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Eliminar</button>
                            @endcan
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
        cancelAction="closeModal"
        :title="$isEditing ? 'Editar Usuario' : 'Crear Nuevo Usuario'"
        :submitText="$isEditing ? 'Actualizar Usuario' : 'Crear Usuario'"
    >
        <!-- MODAL DE FORMULARIO -->
    @include('livewire.admin.usuarios-form')
    </x-form-modal>

    <!-- MODAL DE AUDITORÍA -->
    <livewire:components.audit-history />

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

    <x-confirmation-modal :show="$forceDeletingId !== null" entangleProperty="forceDeletingId">
        <x-slot name="title">Eliminar Permanentemente</x-slot>
        <x-slot name="content">¿Estás seguro? Esta acción es irreversible y eliminará al usuario definitivamente.</x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('forceDeletingId', null)">Cancelar</x-secondary-button>
            <x-danger-button class="ml-3" wire:click="performForceDelete">Eliminar Permanentemente</x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    {{-- Modal de Reset de Contraseña --}}
    @if($resettingPasswordId !== null)
    <x-modal :show="true" maxWidth="md">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Restablecer Contraseña
            </h3>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nueva Contraseña</label>
                <div class="flex mt-1">
                    <input type="text" wire:model="newPassword" class="flex-1 block w-full rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Escribe o genera una contraseña">
                    <button wire:click="generatePassword" type="button" class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md bg-gray-50 dark:bg-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-500">
                        <i class="fas fa-random mr-2"></i> Generar
                    </button>
                </div>
                @error('newPassword') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Asegúrate de copiar esta contraseña y enviársela al usuario.
                </p>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button wire:click="$set('resettingPasswordId', null)">
                    Cancelar
                </x-secondary-button>

                <x-primary-button wire:click="performPasswordReset">
                    Guardar Cambios
                </x-primary-button>
            </div>
        </div>
    </x-modal>
    @endif


</div>
