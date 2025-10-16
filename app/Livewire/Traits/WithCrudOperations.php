<?php

namespace App\Livewire\Traits;

use App\Actions\DeleteModelAction;
use App\Actions\ForceDeleteModelAction;
use App\Actions\RestoreModelAction;

/**
 * Trait principal que combina todas las operaciones CRUD estándar.
 * 
 * Este trait agrupa:
 * - HasFormModal: Operaciones de formulario (create, edit, save)
 * - HasSorting: Ordenamiento de tablas
 * - HasTrashToggle: Toggle entre activos y papelera
 * 
 * Además proporciona métodos helper para operaciones CRUD individuales.
 * 
 * REQUISITOS:
 * - El componente debe usar WithPagination
 * - El modelo debe tener SoftDeletes
 * - Debe tener un Form object
 * - Debe implementar métodos abstractos requeridos
 */
trait WithCrudOperations
{
    use HasFormModal;
    use HasSorting;
    use HasTrashToggle;

    // =======================================================================
    //  PROPIEDADES PARA OPERACIONES INDIVIDUALES
    // =======================================================================

    /** @var ?int ID del registro a eliminar (soft delete) */
    public ?int $deletingId = null;

    /** @var ?int ID del registro a restaurar */
    public ?int $restoringId = null;

    /** @var ?int ID del registro a eliminar permanentemente */
    public ?int $forceDeleteingId = null;

    // =======================================================================
    //  MÉTODOS DE ELIMINACIÓN INDIVIDUAL
    // =======================================================================

    /**
     * Confirma la eliminación de un registro (soft delete).
     */
    public function delete(int $id): void
    {
        $modelClass = $this->getModelClass();
        $model = $modelClass::findOrFail($id);
        $this->authorize('delete', $model);
        
        $this->deletingId = $id;
    }

    /**
     * Ejecuta la eliminación suave del registro.
     */
    public function performDelete(): void
    {
        if ($this->deletingId) {
            $modelClass = $this->getModelClass();
            $model = $modelClass::findOrFail($this->deletingId);
            
            $result = app(DeleteModelAction::class)->execute($model);

            $this->deletingId = null;
            $this->dispatch('notify', message: $result['message'], type: 'success');
        }
    }

    /**
     * Confirma la restauración de un registro.
     */
    public function restore(int $id): void
    {
        $modelClass = $this->getModelClass();
        $model = $modelClass::withTrashed()->findOrFail($id);
        $this->authorize('restore', $model);
        
        $this->restoringId = $id;
    }

    /**
     * Ejecuta la restauración del registro.
     */
    public function performRestore(): void
    {
        if ($this->restoringId) {
            $modelClass = $this->getModelClass();
            $model = $modelClass::withTrashed()->findOrFail($this->restoringId);
            
            $result = app(RestoreModelAction::class)->execute($model);

            $this->restoringId = null;
            $this->dispatch('notify', message: $result['message'], type: 'success');
        }
    }

    /**
     * Confirma la eliminación permanente de un registro.
     */
    public function forceDelete(int $id): void
    {
        $modelClass = $this->getModelClass();
        $model = $modelClass::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $model);
        
        $this->forceDeleteingId = $id;
    }

    /**
     * Ejecuta la eliminación permanente del registro.
     */
    public function performForceDelete(): void
    {
        if ($this->forceDeleteingId) {
            $modelClass = $this->getModelClass();
            $model = $modelClass::withTrashed()->findOrFail($this->forceDeleteingId);
            
            $result = app(ForceDeleteModelAction::class)->execute($model);

            $this->forceDeleteingId = null;
            $this->dispatch('notify', message: $result['message'], type: 'success');
        }
    }

    // =======================================================================
    //  MÉTODO HELPER PARA CERRAR MODALES
    // =======================================================================

    /**
     * Método genérico para cerrar cualquier modal desde Alpine.
     */
    public function closeModal(string $modalProperty): void
    {
        $this->{$modalProperty} = match($modalProperty) {
            'showFormModal', 
            'confirmingBulkDelete', 
            'confirmingBulkRestore', 
            'confirmingBulkForceDelete' => false,
            default => null,
        };
        
        if ($modalProperty === 'showFormModal') {
            $this->form->reset();
            $this->clearRecentlyCreated();
        }
    }

    // =======================================================================
    //  HOOKS PARA LIMPIAR ESTADO
    // =======================================================================

    /**
     * Hook antes de ordenar - limpia registro recién creado.
     */
    protected function beforeSort(): void
    {
        $this->clearRecentlyCreated();
    }

    /**
     * Hook antes de cambiar vista - limpia registro recién creado.
     */
    protected function beforeToggleTrash(): void
    {
        $this->clearRecentlyCreated();
    }
}
