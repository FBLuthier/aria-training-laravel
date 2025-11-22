<?php

namespace App\Policies;

/**
 * =======================================================================
 * POLICY DE AUTORIZACIÓN PARA EJERCICIOS
 * =======================================================================
 * 
 * Esta Policy controla quién puede realizar operaciones sobre Ejercicios.
 * Extiende de BaseAdminPolicy, que implementa el patrón "solo administradores".
 * 
 * AUTORIZACIÓN:
 * - Hereda todos los permisos de administrador de BaseAdminPolicy.
 * - viewAny, view, create, update, delete, restore, forceDelete.
 * 
 * @package App\Policies
 */
class EjercicioPolicy extends BaseAdminPolicy
{
    // Hereda toda la lógica de BaseAdminPolicy
}
