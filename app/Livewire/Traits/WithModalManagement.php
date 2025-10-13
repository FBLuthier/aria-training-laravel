<?php

namespace App\Livewire\Traits;

/**
 * =========================================================================
 * TRAIT PARA LA GESTIÓN GENÉRICA DE MODALES DE CONFIRMACIÓN
 * =========================================================================
 * Este Trait encapsula la lógica para manejar modales de confirmación
 * que requieren que un usuario apruebe una acción sobre un registro específico.
 *
 * CÓMO FUNCIONA:
 * 1. `confirmAction`: Abre el modal. Guarda el ID del registro y el nombre
 * del método que debe ejecutarse si el usuario confirma.
 * 2. `resolveAction`: Es llamado por el botón de confirmación. Ejecuta el método
 * guardado en el paso 1 y luego cierra el modal.
 * 3. `cancelAction`: Cierra el modal y resetea el estado.
 */
trait WithModalManagement
{
    /** @var ?int El ID del registro sobre el que se va a actuar. Si es no nulo, un modal está activo. */
    public ?int $modalConfirmingId = null;

    /** @var ?string El nombre del método de acción que se debe ejecutar al confirmar. */
    public ?string $modalActionName = null;

    /**
     * Prepara y muestra el modal de confirmación.
     *
     * @param int $id El ID del registro.
     * @param string $actionName El nombre del método a ejecutar (ej. 'deleteEquipo').
     */
    public function confirmAction(int $id, string $actionName): void
    {
        $this->modalConfirmingId = $id;
        $this->modalActionName = $actionName;
    }

    /**
     * Cierra el modal de confirmación y resetea las propiedades.
     */
    public function cancelAction(): void
    {
        $this->modalConfirmingId = null;
        $this->modalActionName = null;
    }

    /**
     * Ejecuta el método de acción guardado y cierra el modal.
     * Utiliza una llamada de método dinámica.
     */
    public function resolveAction(): void
    {
        if ($this->modalActionName) {
            // Esto es una llamada de método dinámica. Si $modalActionName es 'deleteEquipo',
            // esta línea es equivalente a llamar a `$this->deleteEquipo()`.
            $this->{$this->modalActionName}();
        }
    }
}