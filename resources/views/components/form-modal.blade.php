@props([
    'show' => false,
    'maxWidth' => '2xl',
    'title' => '',
    'submitText' => 'Guardar',
    'cancelText' => 'Cancelar',
    'cancelAction' => null
])

<x-modal :show="$show" :maxWidth="$maxWidth" entangleProperty="showFormModal">
    <div class="p-6 dark:bg-gray-800">
        {{-- Título del Modal --}}
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
            {{ $title }}
        </h2>

        {{-- Contenido del formulario --}}
        <form wire:submit.prevent="save" class="space-y-4">
            {{ $slot }}

            {{-- Botones de acción --}}
            <div class="flex justify-end gap-3 pt-4">
                @if($cancelAction)
                    <x-secondary-button type="button" wire:click="{{ $cancelAction }}">
                        {{ $cancelText }}
                    </x-secondary-button>
                @endif
                <x-primary-button type="submit">
                    {{ $submitText }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-modal>
