<?php

namespace App\Livewire\Traits;

/**
 * Trait para manejar operaciones de formulario modal.
 * 
 * Este trait proporciona la funcionalidad estándar para:
 * - Abrir modal para crear
 * - Abrir modal para editar
 * - Guardar (crear o actualizar)
 * - Cerrar modal
 * - Manejar auditoría de cambios
 * 
 * REQUISITOS:
 * - El componente debe tener una propiedad Form (ej: EquipoForm)
 * - Debe implementar getModelClass() que retorne el nombre de la clase del modelo
 * - Debe tener propiedades: $showFormModal, $form
 */
trait HasFormModal
{
    /** @var bool Controla la visibilidad del modal de formulario */
    public bool $showFormModal = false;

    /**
     * Listener para detectar cambios en showFormModal desde Alpine.
     */
    public function updatedShowFormModal($value): void
    {
        if (!$value) {
            $this->form->reset();
            $this->clearRecentlyCreated();
        }
    }

    /**
     * Abre el modal para crear un nuevo registro.
     */
    public function create(): void
    {
        $modelClass = $this->getModelClass();
        $this->authorize('create', $modelClass);
        
        $this->form->reset();
        $this->showFormModal = true;
        $this->clearRecentlyCreated();
    }

    /**
     * Abre el modal para editar un registro existente.
     */
    public function edit(int $id): void
    {
        $modelClass = $this->getModelClass();
        $model = $modelClass::findOrFail($id);
        $this->authorize('update', $model);
        
        $this->setFormModel($model);
        $this->showFormModal = true;
    }

    /**
     * Guarda el registro (crear o actualizar).
     */
    public function save(): void
    {
        // Autorización para crear o actualizar
        $isUpdating = $this->form->{$this->getFormModelProperty()} 
            && $this->form->{$this->getFormModelProperty()}->exists;

        if ($isUpdating) {
            $this->authorize('update', $this->form->{$this->getFormModelProperty()});
        } else {
            $this->authorize('create', $this->getModelClass());
        }
        
        // Guardar valores anteriores para auditoría en caso de actualización
        $oldValues = $isUpdating 
            ? $this->form->{$this->getFormModelProperty()}->toArray()
            : null;

        $message = $this->form->save();

        // Auditoría
        $this->auditFormSave($oldValues);

        // Marcar como recién creado si aplica
        if ($this->form->{$this->getFormModelProperty()}->wasRecentlyCreated) {
            $this->markAsRecentlyCreated($this->form->{$this->getFormModelProperty()});
        }

        $this->closeFormModal();
        $this->dispatch('notify', message: $message, type: 'success');
    }

    /**
     * Cierra el modal del formulario.
     */
    public function closeFormModal(): void
    {
        $this->showFormModal = false;
        $this->form->reset();
    }

    /**
     * Método abstracto: Debe retornar la clase del modelo.
     */
    abstract protected function getModelClass(): string;

    /**
     * Método abstracto: Debe establecer el modelo en el formulario.
     */
    abstract protected function setFormModel($model): void;

    /**
     * Método abstracto: Debe realizar la auditoría después de guardar.
     */
    abstract protected function auditFormSave(?array $oldValues): void;

    /**
     * Obtiene el nombre de la propiedad del modelo en el form.
     * Por defecto es el nombre del modelo en minúsculas.
     * Puede ser sobrescrito si es necesario.
     */
    protected function getFormModelProperty(): string
    {
        $modelClass = $this->getModelClass();
        return strtolower(class_basename($modelClass));
    }

    /**
     * Marca un modelo como recién creado (para resaltado en UI).
     * Por defecto no hace nada, puede ser sobrescrito.
     */
    protected function markAsRecentlyCreated($model): void
    {
        // Override en el componente si necesitas esta funcionalidad
    }

    /**
     * Limpia el marcador de recién creado.
     * Por defecto no hace nada, puede ser sobrescrito.
     */
    protected function clearRecentlyCreated(): void
    {
        // Override en el componente si necesitas esta funcionalidad
    }
}
