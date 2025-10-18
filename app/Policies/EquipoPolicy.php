<?php

namespace App\Policies;

/**
 * =======================================================================
 * POLICY DE AUTORIZACIÓN PARA EQUIPOS
 * =======================================================================
 * 
 * Esta Policy controla quién puede realizar operaciones sobre Equipos.
 * Extiende de BaseAdminPolicy, que implementa el patrón "solo administradores".
 * 
 * AUTORIZACIÓN ACTUAL:
 * - viewAny (ver lista): Solo administradores ✓
 * - view (ver detalle): Solo administradores ✓
 * - create (crear): Solo administradores ✓
 * - update (editar): Solo administradores ✓
 * - delete (eliminar): Solo administradores ✓
 * - restore (restaurar): Solo administradores ✓
 * - forceDelete (eliminar permanente): Solo administradores ✓
 * - export (exportar): Solo administradores ✓
 * 
 * CÓMO FUNCIONA:
 * Laravel automáticamente verifica estas policies en:
 * - Gates: Gate::allows('update', $equipo)
 * - Middleware: Route::middleware('can:update,equipo')
 * - Controllers: $this->authorize('update', $equipo)
 * - Livewire: $this->authorize('update', $equipo)
 * 
 * ESTA CLASE ESTÁ VACÍA INTENCIONALMENTE:
 * Toda la lógica viene de BaseAdminPolicy. La mantenemos porque:
 * 1. Es la convención de Laravel (1 Policy por modelo)
 * 2. Permite agregar lógica personalizada en el futuro
 * 
 * EJEMPLO DE PERSONALIZACIÓN FUTURA:
 * ```php
 * public function update(User $user, Model $model): bool
 * {
 *     // Permitir que entrenadores editen solo ciertos campos
 *     if ($user->isEntrenador()) {
 *         return true; // Validar campos en el Form
 *     }
 *     
 *     return $this->isAdmin($user);
 * }
 * ```
 * 
 * @package App\Policies
 * @since 1.0
 */
class EquipoPolicy extends BaseAdminPolicy
{
    // =======================================================================
    //  HERENCIA COMPLETA DE BaseAdminPolicy
    // =======================================================================
    
    // Todos los métodos de autorización están implementados en BaseAdminPolicy.
    // No es necesario sobrescribir nada a menos que necesites lógica especial.
    
    // MÉTODOS HEREDADOS:
    // - viewAny(): Solo administradores
    // - view(): Solo administradores
    // - create(): Solo administradores
    // - update(): Solo administradores
    // - delete(): Solo administradores
    // - restore(): Solo administradores
    // - forceDelete(): Solo administradores
    // - export(): Solo administradores
}
