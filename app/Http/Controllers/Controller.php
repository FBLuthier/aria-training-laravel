<?php

namespace App\Http\Controllers;

/**
 * =======================================================================
 * CONTROLADOR BASE
 * =======================================================================
 * 
 * Clase base abstracta para todos los controllers de la aplicación.
 * Laravel 11+ simplificó la arquitectura removiendo métodos heredados.
 * 
 * PROPÓSITO:
 * Servir como clase padre para controllers personalizados.
 * Permite agregar funcionalidad compartida si es necesario.
 * 
 * USO:
 * ```php
 * class MiController extends Controller
 * {
 *     public function index()
 *     {
 *         // Lógica del controller
 *     }
 * }
 * ```
 * 
 * NOTA: En Laravel 11+, esta clase está intencionalmente vacía.
 * Los traits y middleware se configuran en otros lugares.
 * 
 * @package App\Http\Controllers
 * @since 1.0
 */
abstract class Controller
{
    // Clase base vacía - extender según necesidades
}
