<?php

namespace App\Livewire\Traits;

use App\Actions\DeleteModelAction;
use App\Actions\ForceDeleteModelAction;
use App\Actions\RestoreModelAction;

/**
 * =======================================================================
 * TRAIT PRINCIPAL DE OPERACIONES CRUD
 * =======================================================================
 * 
 * Este trait combina toda la funcionalidad necesaria para operaciones CRUD
 * completas en componentes Livewire. Es el "cerebro" de los CRUDs.
 * 
 * TRAITS INCLUIDOS:
 * - HasFormModal: Gestión de modales de formulario (crear/editar/guardar)
 * - HasSorting: Ordenamiento de columnas en tablas
 * - HasTrashToggle: Cambio entre vista activa y papelera
 * 
 * FUNCIONALIDADES PROPIAS:
 * - Eliminación suave (soft delete) de registros individuales
 * - Restauración de registros desde la papelera
 * - Eliminación permanente (force delete) de registros
 * - Gestión de modales de confirmación
 * - Limpieza de estado cuando cambia el contexto
 * 
 * FLUJO DE ELIMINACIÓN:
 * 1. Usuario hace clic en "Eliminar" → delete($id)
 * 2. Se muestra modal de confirmación
 * 3. Usuario confirma → performDelete()
 * 4. Se ejecuta DeleteModelAction
 * 5. Se cierra modal y muestra notificación
 * 
 * REQUISITOS DEL COMPONENTE:
 * - Usar WithPagination de Livewire
 * - El modelo debe tener SoftDeletes
 * - Tener propiedad pública $form (Form object)
 * - Implementar getModelClass(): string
 * - Implementar clearRecentlyCreated(): void
 * 
 * USO:
 * ```php
 * class GestionarEquipos extends Component
 * {
 *     use WithCrudOperations;
 *     
 *     public EquipoForm $form;
 *     
 *     protected function getModelClass(): string
 *     {
 *         return Equipo::class;
 *     }
 * }
 * ```
 * 
 * @package App\Livewire\Traits
 * @since 1.0
 */
trait WithCrudOperations
{
    // Traits que componen esta funcionalidad
    use HasFormModal;      // Gestión de formularios
    use HasSorting;        // Ordenamiento de tablas
    use HasTrashToggle;    // Toggle papelera/activos

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
     * 
     * Este método solo prepara la eliminación, mostrando un modal
     * de confirmación. La eliminación real ocurre en performDelete().
     * 
     * @param int $id ID del registro a eliminar
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException Si no tiene permiso
     */
    public function delete(int $id): void
    {
        // Obtener la clase del modelo (ej: Equipo::class)
        $modelClass = $this->getModelClass();
        
        // Buscar el registro o fallar con 404
        $model = $modelClass::findOrFail($id);
        
        // Verificar que el usuario tiene permiso para eliminar
        $this->authorize('delete', $model);
        
        // Guardar ID para mostrar modal de confirmación
        $this->deletingId = $id;
    }

    /**
     * Ejecuta la eliminación suave del registro.
     * 
     * Este método se llama cuando el usuario confirma la eliminación
     * en el modal. Ejecuta la acción y muestra notificación.
     * 
     * NOTA: El registro NO se elimina permanentemente, solo se marca
     * como eliminado (deleted_at). Puede restaurarse después.
     * 
     * @return void
     */
    public function performDelete(): void
    {
        // Verificar que hay un ID pendiente de eliminar
        if ($this->deletingId) {
            // Obtener clase del modelo
            $modelClass = $this->getModelClass();
            
            // Buscar el registro
            $model = $modelClass::findOrFail($this->deletingId);
            
            // Ejecutar acción de eliminación (soft delete)
            $result = app(DeleteModelAction::class)->execute($model);

            // Limpiar estado
            $this->deletingId = null;
            $this->clearRecentlyCreated(); // Quitar resaltado si existía
            
            // Mostrar notificación de éxito
            $this->dispatch('notify', message: $result['message'], type: 'success');
        }
    }

    /**
     * Confirma la restauración de un registro desde la papelera.
     * 
     * Muestra modal de confirmación antes de restaurar.
     * 
     * @param int $id ID del registro a restaurar
     * @return void
     */
    public function restore(int $id): void
    {
        $modelClass = $this->getModelClass();
        
        // Buscar incluyendo eliminados (withTrashed)
        $model = $modelClass::withTrashed()->findOrFail($id);
        $this->authorize('restore', $model);
        
        $this->restoringId = $id;
    }

    /**
     * Ejecuta la restauración del registro.
     * 
     * Restaura un registro previamente eliminado (soft deleted).
     * El registro vuelve a estar activo (deleted_at = null).
     * 
     * @return void
     */
    public function performRestore(): void
    {
        if ($this->restoringId) {
            $modelClass = $this->getModelClass();
            $model = $modelClass::withTrashed()->findOrFail($this->restoringId);
            
            // Ejecutar acción de restauración
            $result = app(RestoreModelAction::class)->execute($model);

            $this->restoringId = null;
            $this->clearRecentlyCreated();
            $this->dispatch('notify', message: $result['message'], type: 'success');
        }
    }

    /**
     * Confirma la eliminación permanente de un registro.
     * 
     * ADVERTENCIA: Esta acción NO es reversible.
     * El registro se eliminará completamente de la base de datos.
     * 
     * @param int $id ID del registro a eliminar permanentemente
     * @return void
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
     * 
     * PELIGRO: Este método elimina el registro PERMANENTEMENTE.
     * No puede deshacerse. Use con precaución.
     * 
     * @return void
     */
    public function performForceDelete(): void
    {
        if ($this->forceDeleteingId) {
            $modelClass = $this->getModelClass();
            $model = $modelClass::withTrashed()->findOrFail($this->forceDeleteingId);
            
            // Eliminar permanentemente de la base de datos
            $result = app(ForceDeleteModelAction::class)->execute($model);

            $this->forceDeleteingId = null;
            $this->clearRecentlyCreated();
            $this->dispatch('notify', message: $result['message'], type: 'success');
        }
    }

    // =======================================================================
    //  MÉTODO HELPER PARA CERRAR MODALES
    // =======================================================================

    /**
     * Cierra cualquier modal desde Alpine.js.
     * 
     * Este método es llamado por Alpine.js para cerrar modales
     * y resetear el estado asociado.
     * 
     * @param string $modalProperty Nombre de la propiedad del modal
     * @return void
     */
    public function closeModal(string $modalProperty): void
    {
        // Establecer valor según el tipo de propiedad
        $this->{$modalProperty} = match($modalProperty) {
            'showFormModal', 
            'confirmingBulkDelete', 
            'confirmingBulkRestore', 
            'confirmingBulkForceDelete' => false,
            default => null,
        };
        
        // Si es el modal de formulario, resetear el form
        if ($modalProperty === 'showFormModal') {
            $this->form->reset();
            $this->clearRecentlyCreated();
        }
    }

    // =======================================================================
    //  HOOKS PARA LIMPIAR ESTADO
    // =======================================================================

    /**
     * Hook ejecutado antes de ordenar columnas.
     * 
     * Limpia el registro resaltado porque al ordenar
     * cambia la posición de todos los registros.
     * 
     * @return void
     */
    protected function beforeSort(): void
    {
        $this->clearRecentlyCreated();
    }

    /**
     * Hook ejecutado antes de cambiar entre activos/papelera.
     * 
     * Limpia el registro resaltado porque al cambiar de vista
     * el registro puede no estar visible.
     * 
     * @return void
     */
    protected function beforeToggleTrash(): void
    {
        $this->clearRecentlyCreated();
    }
}
