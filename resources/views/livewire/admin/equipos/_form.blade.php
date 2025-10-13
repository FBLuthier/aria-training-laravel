{{-- 
    Este es un formulario parcial para ser incluido en un modal.
    Utiliza el "Form Object" ($form) del componente Livewire para
    determinar si se está creando o editando un equipo y ajusta
    dinámicamente los textos y acciones.
--}}
<form wire:submit="save" class="p-6">

    {{-- Título dinámico del modal --}}
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ $form->exists ? 'Editar Equipo' : 'Crear Nuevo Equipo' }}
    </h2>

    {{-- Campo de texto para el nombre del equipo --}}
    <div class="mt-6">
        <x-input-label for="nombre-equipo" value="Nombre" class="sr-only" />
        <x-text-input 
            wire:model="form.nombre" 
            id="nombre-equipo" 
            class="mt-1 block w-full" 
            type="text" 
            placeholder="Nombre del equipo" 
        />
        <x-input-error :messages="$errors->get('form.nombre')" class="mt-2" />
    </div>

    {{-- Botones de acción del formulario --}}
    <div class="mt-6 flex justify-end">
        <x-secondary-button x-on:click="$dispatch('close')">
            Cancelar
        </x-secondary-button>

        {{-- El texto del botón principal cambia según el contexto (crear/editar) --}}
        <x-primary-button class="ml-3">
            {{ $form->exists ? 'Guardar Cambios' : 'Crear Equipo' }}
        </x-primary-button>
    </div>
</form>