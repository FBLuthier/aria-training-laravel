<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * =======================================================================
 * CONTROLLER: AUTENTICACIÓN DE SESIÓN (LOGIN/LOGOUT)
 * =======================================================================
 * 
 * Maneja el ciclo completo de autenticación de usuarios:
 * inicio de sesión, cierre de sesión y regeneración de sesiones.
 * 
 * RESPONSABILIDADES:
 * - Mostrar formulario de login
 * - Autenticar credenciales del usuario
 * - Iniciar sesión autenticada
 * - Cerrar sesión y limpiar estado
 * 
 * FLUJO DE LOGIN:
 * 1. Usuario accede a /login (método create())
 * 2. Ingresa credenciales y envía formulario
 * 3. LoginRequest valida y autentica (método store())
 * 4. Se regenera ID de sesión (seguridad)
 * 5. Redirige a dashboard o URL prevista
 * 
 * FLUJO DE LOGOUT:
 * 1. Usuario hace clic en "Cerrar Sesión"
 * 2. Se cierra sesión del guard 'web' (método destroy())
 * 3. Se invalida la sesión actual
 * 4. Se regenera token CSRF
 * 5. Redirige a página inicial
 * 
 * SEGURIDAD:
 * - Regeneración de sesión previene session fixation
 * - Validación en LoginRequest
 * - Rate limiting en LoginRequest
 * - Invalidación completa al logout
 * 
 * @package App\Http\Controllers\Auth
 * @since 1.0
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     * 
     * @return View Vista 'auth.login'
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Procesa el intento de autenticación.
     * 
     * Este método:
     * 1. Delega la validación y autenticación a LoginRequest
     * 2. Regenera el ID de sesión (previene session fixation)
     * 3. Redirige al destino previsto o al dashboard
     * 
     * @param LoginRequest $request Request con validación y autenticación
     * @return RedirectResponse Redirige a dashboard o URL prevista
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Autentica las credenciales (lanza excepción si fallan)
        $request->authenticate();

        // Regenera ID de sesión para prevenir ataques
        $request->session()->regenerate();

        // Redirige a la URL prevista o al dashboard
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Cierra la sesión del usuario autenticado.
     * 
     * Realiza limpieza completa:
     * - Cierra sesión del guard 'web'
     * - Invalida sesión actual
     * - Regenera token CSRF
     * 
     * @param Request $request
     * @return RedirectResponse Redirige a página inicial
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Cerrar sesión del guard web
        Auth::guard('web')->logout();

        // Invalidar sesión actual
        $request->session()->invalidate();

        // Regenerar token CSRF
        $request->session()->regenerateToken();

        // Redirigir a home
        return redirect('/');
    }
}
