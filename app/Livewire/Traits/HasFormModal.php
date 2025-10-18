<?php

namespace App\Livewire\Traits;

/**
 * =======================================================================
 * TRAIT PARA GESTIÓN DE FORMULARIOS MODALES
 * =======================================================================
 * 
 * Este trait maneja todo el ciclo de vida de formularios en modales:
 * crear, editar, guardar y cerrar. Es el corazón de las operaciones
 * CRUD con modales.
 * 
 * FUNCIONALIDADES:
 * - create(): Abre modal vacío para crear nuevo registro
 * - edit($id): Abre modal con datos para editar
 * - save(): Guarda (crea o actualiza) el registro
 * - closeFormModal(): Cierra modal y resetea formulario
 * - Auditoría automática de cambios
 * - Marcado de registros recién creados
 * 
 * FLUJO DE CREACIÓN:
 * 1. Usuario hace clic en "Crear" → create()
 * 2. Se abre modal vacío
 * 3. Usuario llena formulario
 * 4. Usuario hace clic en "Guardar" → save()
 * 5. Se valida, crea registro y cierra modal
 * 6. Se muestra notificación de éxito
 * 
 * FLUJO DE EDICIÓN:
 * 1. Usuario hace clic en "Editar" → edit($id)
 * 2. Se carga registro y abre modal con datos
 * 3. Usuario modifica formulario
 * 4. Usuario hace clic en "Guardar" → save()
 * 5. Se valida, actualiza registro y cierra modal
 * 6. Se muestra notificación de éxito
 * 
 * REQUISITOS DEL COMPONENTE:
 * - Propiedad pública $form (BaseModelForm)
 * - Implementar getModelClass(): string
 * - Implementar setFormModel($model): void
 * - Implementar markAsRecentlyCreated($model): void
 * - Implementar clearRecentlyCreated(): void
 * - Implementar auditFormSave($oldValues): void
 * 
 * @package App\Livewire\Traits
 * @since 1.0
 */
trait HasFormModal
{
    // =======================================================================
    //  PROPIEDADES
    // =======================================================================
    
    /** @var bool Controla si el modal de formulario está visible */
    public bool $showFormModal = false;
    
    // =======================================================================
    //  LIFECYCLE HOOKS
    // =======================================================================

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
        $this->clearRecentlyCreated(); // Limpiar item resaltado
    }

    /**
     * Guarda el registro (crear o actualizar).
     */
    public function save(): void
    {
        // Autorización para crear o actualizar
        $isUpdating = $this->form->model && $this->form->model->exists;

        if ($isUpdating) {
            $this->authorize('update', $this->form->model);
        } else {
            $this->authorize('create', $this->getModelClass());
        }
        
        // Guardar valores anteriores para auditoría en caso de actualización
        $oldValues = $isUpdating 
            ? $this->form->model->toArray()
            : null;

        $message = $this->form->save();

        // Auditoría
        $this->auditFormSave($oldValues);

        // Marcar como recién creado si aplica
        if ($this->form->model->wasRecentlyCreated) {
            $this->markAsRecentlyCreated($this->form->model);
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
