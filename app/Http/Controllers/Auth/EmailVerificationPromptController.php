<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * =======================================================================
 * CONTROLLER: PANTALLA DE VERIFICACIÓN DE EMAIL
 * =======================================================================
 *
 * Muestra la pantalla que solicita al usuario verificar su email.
 * Se usa cuando el usuario registrado aún no ha verificado su correo.
 *
 * RESPONSABILIDADES:
 * - Verificar si email ya está verificado
 * - Mostrar pantalla de verificación si no está verificado
 * - Redirigir a dashboard si ya está verificado
 *
 * CUÁNDO SE USA:
 * Este controller se invoca cuando:
 * - Usuario recién registrado intenta acceder a rutas protegidas
 * - Middleware 'verified' detecta email no verificado
 * - Redirige aquí para solicitar verificación
 *
 * FLUJO:
 * 1. Usuario registrado intenta acceder a ruta protegida
 * 2. Middleware verifica estado de email
 * 3. Si no verificado, redirige aquí
 * 4. Muestra pantalla con opción de reenviar email
 * 5. Usuario puede hacer clic en "Reenviar" (EmailVerificationNotificationController)
 *
 * @since 1.0
 */
class EmailVerificationPromptController extends Controller
{
    /**
     * Muestra la pantalla de verificación de email o redirige si ya verificado.
     *
     * Este es un invokable controller (single action controller).
     *
     * @return RedirectResponse|View Redirige a dashboard si verificado, o muestra vista
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        // Si email ya verificado, redirigir a dashboard
        // Si no, mostrar vista de verificación
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('dashboard', absolute: false))
                    : view('auth.verify-email');
    }
}
