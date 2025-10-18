@props([
    'entityName' => 'registro',
    'entityNamePlural' => 'registros',
    'form' => null,
])

{{-- Modal de Formulario (Crear/Editar) --}}
@if($form)
    <x-form-modal 
        :show="$showFormModal"
        cancelAction="closeFormModal"
        :title="$form->model?->exists ? 'Editar ' . ucfirst($entityName) : 'Crear Nuevo ' . ucfirst($entityName)"
        :submitText="$form->model?->exists ? 'Guardar Cambios' : 'Crear ' . ucfirst($entityName)"
    >
        {{ $formFields ?? '' }}
    </x-form-modal>
@endif

{{-- Modal de Confirmación: Eliminar Individual --}}
<x-confirmation-modal :show="$deletingId !== null" entangleProperty="deletingId">
    <x-slot name="title">Eliminar {{ ucfirst($entityName) }}</x-slot>
    <x-slot name="content">
        ¿Estás seguro de que deseas eliminar este {{ $entityName }}? Se moverá a la papelera y podrás restaurarlo más tarde.
    </x-slot>
    <x-slot name="footer">
        <x-secondary-button wire:click="$set('deletingId', null)">Cancelar</x-secondary-button>
        <x-danger-button class="ml-3" wire:click="performDelete" loadingTarget="performDelete">Eliminar</x-danger-button>
    </x-slot>
</x-confirmation-modal>

{{-- Modal de Confirmación: Restaurar Individual --}}
<x-confirmation-modal :show="$restoringId !== null" entangleProperty="restoringId">
    <x-slot name="title">Restaurar {{ ucfirst($entityName) }}</x-slot>
    <x-slot name="content">
        ¿Estás seguro de que deseas restaurar este {{ $entityName }}?
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
        ¿Estás seguro de que deseas eliminar este {{ $entityName }} permanentemente?
    </x-slot>
    <x-slot name="footer">
        <x-secondary-button wire:click="$set('forceDeleteingId', null)">Cancelar</x-secondary-button>
        <x-danger-button class="ml-3" wire:click="performForceDelete" loadingTarget="performForceDelete">Eliminar Permanentemente</x-danger-button>
    </x-slot>
</x-confirmation-modal>

{{-- Modal de Confirmación: Eliminar en Lote --}}
<x-confirmation-modal :show="$confirmingBulkDelete" entangleProperty="confirmingBulkDelete">
    <x-slot name="title">Eliminar {{ ucfirst($entityNamePlural) }} Seleccionados</x-slot>
    <x-slot name="content">
        ¿Estás seguro de que deseas eliminar <strong>{{ $this->selectedCount }}</strong> {{ $entityName }}(s)? Se moverán a la papelera.
        @if($selectingAll)
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Se eliminarán <strong>todos</strong> los {{ $entityNamePlural }} que coinciden con los filtros actuales.
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
    <x-slot name="title">Restaurar {{ ucfirst($entityNamePlural) }} Seleccionados</x-slot>
    <x-slot name="content">
        ¿Estás seguro de que deseas restaurar <strong>{{ $this->selectedCount }}</strong> {{ $entityName }}(s)?
        @if($selectingAll)
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Se restaurarán <strong>todos</strong> los {{ $entityNamePlural }} que coinciden con los filtros actuales.
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
    <x-slot name="title">Eliminar Permanentemente {{ ucfirst($entityNamePlural) }} Seleccionados</x-slot>
    <x-slot name="content">
        <strong class="text-red-600 dark:text-red-400">¡Esta acción no se puede deshacer!</strong><br>
        ¿Estás seguro de que deseas eliminar permanentemente <strong>{{ $this->selectedCount }}</strong> {{ $entityName }}(s)?
        @if($selectingAll)
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Se eliminarán permanentemente <strong>todos</strong> los {{ $entityNamePlural }} que coinciden con los filtros actuales.
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
