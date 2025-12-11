<?php

namespace App\Livewire\Forms;

use Illuminate\Database\Eloquent\Model;
use Livewire\Form;

/**
 * Clase abstracta base para todos los formularios de modelos.
 *
 * Proporciona funcionalidad común para crear y actualizar modelos,
 * eliminando duplicación de código en formularios específicos.
 *
 * @property ?Model $model El modelo que está siendo editado (null si es creación)
 */
abstract class BaseModelForm extends Form
{
    /**
     * El modelo siendo editado/creado.
     */
    public ?Model $model = null;

    // =======================================================================
    //  MÉTODOS ABSTRACTOS (DEBEN SER IMPLEMENTADOS POR CLASES HIJAS)
    // =======================================================================

    /**
     * Retorna las reglas de validación para el formulario.
     */
    abstract protected function rules(): array;

    /**
     * Retorna la clase del modelo que maneja este formulario.
     */
    abstract protected function getModelClass(): string;

    /**
     * Rellena las propiedades del formulario desde un modelo existente.
     */
    abstract protected function fillFromModel(Model $model): void;

    /**
     * Retorna los datos del formulario listos para guardar en el modelo.
     */
    abstract protected function getModelData(): array;

    // =======================================================================
    //  MÉTODOS CON IMPLEMENTACIÓN POR DEFECTO (PUEDEN SER SOBRESCRITOS)
    // =======================================================================

    /**
     * Retorna el mensaje mostrado al crear un nuevo registro.
     */
    protected function getCreateMessage(): string
    {
        $modelName = class_basename($this->getModelClass());

        return "{$modelName} creado exitosamente.";
    }

    /**
     * Retorna el mensaje mostrado al actualizar un registro.
     */
    protected function getUpdateMessage(): string
    {
        $modelName = class_basename($this->getModelClass());

        return "{$modelName} actualizado exitosamente.";
    }

    /**
     * Hook ejecutado antes de validar (útil para transformaciones).
     */
    protected function beforeValidation(): void
    {
        // Por defecto no hace nada, las clases hijas pueden sobrescribir
    }

    /**
     * Hook ejecutado antes de guardar (útil para lógica adicional).
     */
    protected function beforeSave(): void
    {
        // Por defecto no hace nada, las clases hijas pueden sobrescribir
    }

    /**
     * Hook ejecutado después de guardar (útil para relaciones, eventos, etc).
     */
    protected function afterSave(Model $model): void
    {
        // Por defecto no hace nada, las clases hijas pueden sobrescribir
    }

    // =======================================================================
    //  MÉTODOS PÚBLICOS (NO REQUIEREN SOBRESCRITURA)
    // =======================================================================

    /**
     * Establece el modelo en el formulario para edición.
     */
    public function setModel(?Model $model): void
    {
        $this->model = $model;

        if ($model) {
            $this->fillFromModel($model);
        }
    }

    /**
     * Guarda el formulario, creando o actualizando según corresponda.
     *
     * @return string Mensaje de éxito
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save(): string
    {
        // Hook antes de validar
        $this->beforeValidation();

        // Validar datos
        $this->validate($this->rules());

        // Hook antes de guardar
        $this->beforeSave();

        // Guardar modelo
        if ($this->model && $this->model->exists) {
            // Actualizar modelo existente
            $this->model->update($this->getModelData());
            $message = $this->getUpdateMessage();
        } else {
            // Crear nuevo modelo
            $modelClass = $this->getModelClass();
            $this->model = $modelClass::create($this->getModelData());
            $message = $this->getCreateMessage();
        }

        // Hook después de guardar
        $this->afterSave($this->model);

        return $message;
    }

    /**
     * Verifica si el formulario está en modo edición.
     */
    public function isEditing(): bool
    {
        return $this->model && $this->model->exists;
    }

    /**
     * Verifica si el formulario está en modo creación.
     */
    public function isCreating(): bool
    {
        return ! $this->isEditing();
    }

    /**
     * Resetea el formulario a su estado inicial.
     * Sobrescribe el método padre para limpiar también el modelo.
     *
     * @param  mixed  ...$properties
     */
    public function reset(...$properties): void
    {
        // Limpiar el modelo primero
        $this->model = null;

        // Llamar al reset del padre para limpiar propiedades
        parent::reset(...$properties);
    }
}
