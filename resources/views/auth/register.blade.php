<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="usuario" :value="__('Usuario')" />
            <x-text-input id="usuario" class="block mt-1 w-full" type="text" name="usuario" :value="old('usuario')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('usuario')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="contrasena" :value="__('Contraseña')" />
            <x-text-input id="contrasena" class="block mt-1 w-full"
                            type="password"
                            name="contrasena"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('contrasena')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="contrasena_confirmation" :value="__('Confirmar Contraseña')" />
            <x-text-input id="contrasena_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="contrasena_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('contrasena_confirmation')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="correo" :value="__('Correo Electrónico')" />
            <x-text-input id="correo" class="block mt-1 w-full" type="email" name="correo" :value="old('correo')" required autocomplete="email" />
            <x-input-error :messages="$errors->get('correo')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="nombre_1" :value="__('Primer Nombre')" />
            <x-text-input id="nombre_1" class="block mt-1 w-full" type="text" name="nombre_1" :value="old('nombre_1')" required />
            <x-input-error :messages="$errors->get('nombre_1')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="nombre_2" :value="__('Segundo Nombre (Opcional)')" />
            <x-text-input id="nombre_2" class="block mt-1 w-full" type="text" name="nombre_2" :value="old('nombre_2')" />
            <x-input-error :messages="$errors->get('nombre_2')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="apellido_1" :value="__('Primer Apellido')" />
            <x-text-input id="apellido_1" class="block mt-1 w-full" type="text" name="apellido_1" :value="old('apellido_1')" required />
            <x-input-error :messages="$errors->get('apellido_1')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="apellido_2" :value="__('Segundo Apellido (Opcional)')" />
            <x-text-input id="apellido_2" class="block mt-1 w-full" type="text" name="apellido_2" :value="old('apellido_2')" />
            <x-input-error :messages="$errors->get('apellido_2')" class="mt-2" />
        </div>
        
        <div class="mt-4">
            <x-input-label for="telefono" :value="__('Teléfono')" />
            <x-text-input id="telefono" class="block mt-1 w-full" type="tel" name="telefono" :value="old('telefono')" required />
            <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="fecha_nacimiento" :value="__('Fecha de Nacimiento')" />
            <x-text-input id="fecha_nacimiento" class="block mt-1 w-full" type="date" name="fecha_nacimiento" :value="old('fecha_nacimiento')" required />
            <x-input-error :messages="$errors->get('fecha_nacimiento')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>