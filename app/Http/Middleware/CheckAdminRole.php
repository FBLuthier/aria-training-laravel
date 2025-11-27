<?php

namespace App\Http\Middleware;

// --- IMPORTACIONES DE CLASES ---
use Closure;                          // Clase que representa la siguiente acción en la pila de middleware.
use Illuminate\Http\Request;            // Clase para manejar las peticiones HTTP entrantes.
use Illuminate\Support\Facades\Auth;    // Fachada para interactuar con el sistema de autenticación.
use Symfony\Component\HttpFoundation\Response; // Componente base para las respuestas HTTP.

/**
 * =========================================================================
 * MIDDLEWARE DE VERIFICACIÓN DE ROL DE ADMINISTRADOR
 * =========================================================================
 * Este middleware actúa como un punto de control para las rutas. Su única
 * responsabilidad es verificar si el usuario que intenta acceder a una ruta
 * tiene el rol de "Administrador". Si no lo tiene, se le deniega el acceso
 * y es redirigido.
 */
class CheckAdminRole
{
    /**
     * Maneja una petición entrante.
     * Este es el método principal que se ejecuta cuando se aplica el middleware a una ruta.
     *
     * @param  \Illuminate\Http\Request  $request La petición HTTP que se está procesando.
     * @param  \Closure  $next La siguiente capa de middleware en la pila. Laravel inyecta esto automáticamente.
     * @return \Symfony\Component\HttpFoundation\Response La respuesta HTTP, que puede ser la siguiente petición o una redirección.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. VERIFICACIÓN DE CONDICIONES
        // Se comprueban dos cosas en una sola línea:
        //    a) Auth::check(): ¿Hay un usuario con sesión iniciada?
        //    b) Auth::user()->id_tipo_usuario == 1: Si hay un usuario, ¿su `id_tipo_usuario` es igual a 1 (el ID para Administradores)?
        //
        // Ambas condiciones deben ser verdaderas para que el bloque `if` se ejecute.
        // Permitir acceso a Administradores (1) y Entrenadores (2)
        if (Auth::check() && (Auth::user()->tipo_usuario_id == 1 || Auth::user()->tipo_usuario_id == 2)) {
            
            // 2. ACCESO PERMITIDO
            // Si el usuario es un administrador, se ejecuta `$next($request)`.
            // Esto le pasa el control a la siguiente capa de la aplicación (otro middleware o el controlador final de la ruta).
            // En resumen: "Puedes pasar".
            return $next($request);
        }

        // 3. ACCESO DENEGADO
        // Si la condición del `if` no se cumple (el usuario no ha iniciado sesión o no es administrador),
        // se ejecuta esta línea.
        // Se crea una redirección a la ruta '/dashboard', que es la vista general para usuarios no administradores.
        // En resumen: "No puedes pasar, vuelve a tu dashboard".
        return redirect('/dashboard');
    }
}