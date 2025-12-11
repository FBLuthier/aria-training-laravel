<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- COLUMNA 1: DATOS DE CUENTA -->
    <div class="mb-4">
        <!-- FOTO DE PERFIL -->
        <div class="mb-6" x-data="{photoName: null, photoPreview: null}">
            <input type="file" class="hidden"
                        wire:model.live="form.photo"
                        x-ref="photo"
                        x-on:change="
                                photoName = $refs.photo.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    photoPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.photo.files[0]);
                        " />

            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto de Perfil</label>

            <div class="flex items-center">
                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    @if($isEditing && $this->form->userModel)
                        <img src="{{ $this->form->userModel->profile_photo_url }}" alt="Current Profile Photo" class="rounded-full h-20 w-20 object-cover border-2 border-gray-200 dark:border-gray-600">
                    @else
                        <div class="rounded-full h-20 w-20 bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                    @endif
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center border-2 border-blue-500"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <div class="ml-4">
                    <x-secondary-button class="mr-2" type="button" x-on:click.prevent="$refs.photo.click()">
                        Seleccionar Foto
                    </x-secondary-button>
                    @error('form.photo') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ __('users.form.account_data') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Usuario -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('users.form.username') }}</label>
                <input type="text" wire:model="form.usuario" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('form.usuario') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Correo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('users.form.email') }}</label>
                <input type="email" wire:model="form.correo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('form.correo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Rol (Solo Admin puede cambiarlo) -->
            @admin
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('users.form.role') }}</label>
                <select wire:model.live="form.tipo_usuario_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Seleccione un Rol</option>
                    @foreach($tipos_usuario_list as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->rol }}</option>
                    @endforeach
                </select>
                @error('form.tipo_usuario_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            @else
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('users.form.role') }}</label>
                    @if(auth()->user()->esEntrenador())
                        <!-- Si es Entrenador, el rol es fijo: Atleta -->
                        <div class="block mt-1 w-full p-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300">
                            Atleta
                        </div>
                    @else
                        <select wire:model.live="form.tipo_usuario_id" id="tipo_usuario_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Seleccione un Rol</option>
                            @foreach($tipos_usuario_list as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->rol }}</option>
                            @endforeach
                        </select>
                    @endif
                    @error('form.tipo_usuario_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            @endadmin

            <!-- Entrenador Asignado (Solo si es Atleta) -->
            @if($form->tipo_usuario_id == \App\Enums\UserRole::Atleta->value && !auth()->user()->esEntrenador())
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('users.form.trainer') }}</label>
                <select wire:model="form.entrenador_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">{{ __('users.form.select_trainer') }}</option>
                    @foreach($entrenadores_list as $entrenador)
                        <option value="{{ $entrenador->id }}">{{ $entrenador->nombre_1 }} {{ $entrenador->apellido_1 }} ({{ $entrenador->usuario }})</option>
                    @endforeach
                </select>
                @error('form.entrenador_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            @endif
        </div>
    </div>

    <!-- Sección 2: Datos Personales -->
    <div class="mb-4">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ __('users.form.personal_data') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Nombre 1 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('users.form.first_name') }}</label>
                <input type="text" wire:model="form.nombre_1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('form.nombre_1') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            
            <!-- Nombre 2 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('users.form.second_name') }}</label>
                <input type="text" wire:model="form.nombre_2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('form.nombre_2') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Apellido 1 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('users.form.last_name') }}</label>
                <input type="text" wire:model="form.apellido_1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('form.apellido_1') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Apellido 2 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('users.form.second_last_name') }}</label>
                <input type="text" wire:model="form.apellido_2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('form.apellido_2') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Teléfono -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('users.form.phone') }}</label>
                <input type="text" wire:model="form.telefono" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('form.telefono') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Fecha Nacimiento -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('users.form.birthdate') }}</label>
                <input type="date" wire:model="form.fecha_nacimiento" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('form.fecha_nacimiento') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
</div>

@if(!$isEditing)
    <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-md">
        <p class="text-sm text-blue-700 dark:text-blue-300">
            <i class="fas fa-info-circle mr-2"></i> {{ __('users.form.default_password_note') }}
        </p>
    </div>
@endif


