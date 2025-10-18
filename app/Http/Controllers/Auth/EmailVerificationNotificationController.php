<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * =======================================================================
 * CONTROLLER: REENVÍO DE NOTIFICACIÓN DE VERIFICACIÓN DE EMAIL
 * =======================================================================
 * 
 * Maneja el reenvío del correo de verificación cuando el usuario
 * no ha recibido o ha perdido el email original.
 * 
 * RESPONSABILIDADES:
 * - Verificar si email ya está verificado
 * - Enviar nueva notificación de verificación
 * - Retornar confirmación de envío
 * 
 * FLUJO:
 * 1. Usuario registrado no verifica email
 * 2. Ve pantalla de "Verificar Email"
 * 3. Hace clic en "Reenviar Email"
 * 4. Este controller envía nuevo email
 * 5. Usuario recibe link de verificación
 * 6. Hace clic en link (VerifyEmailController)
 * 
 * SEGURIDAD:
 * - Requiere autenticación
 * - No envía si ya está verificado
 * - Link con firma única
 * - Link con expiración
 * 
 * @package App\Http\Controllers\Auth
 * @since 1.0
 */
class EmailVerificationNotificationController extends Controller
{
    /**
     * Envía una nueva notificación de verificación de email.
     * 
     * Si el email ya está verificado, redirige a dashboard.
     * Si no, envía nuevo email con link de verificación.
     * 
     * @param Request $request
     * @return RedirectResponse Redirige con mensaje de estado
     */
    public function store(Request $request): RedirectResponse
    {
        // Si email ya verificado, redirigir a dashboard
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Enviar nueva notificación de verificación
        $request->user()->sendEmailVerificationNotification();

        // Retornar con mensaje de confirmación
        return back()->with('status', 'verification-link-sent');
    }
}
