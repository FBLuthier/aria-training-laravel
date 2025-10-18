<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

/**
 * =======================================================================
 * CONTROLLER: VERIFICACIÓN DE EMAIL
 * =======================================================================
 * 
 * Maneja la verificación del email cuando el usuario hace clic en el
 * link enviado a su correo electrónico.
 * 
 * RESPONSABILIDADES:
 * - Verificar firma del link (seguridad)
 * - Marcar email como verificado
 * - Disparar evento Verified
 * - Redirigir a dashboard
 * 
 * FLUJO COMPLETO:
 * 1. Usuario se registra (RegisteredUserController)
 * 2. Sistema envía email con link de verificación
 * 3. Usuario hace clic en link del email
 * 4. Este controller valida link y marca email como verificado
 * 5. Usuario puede acceder a todas las funcionalidades
 * 
 * SEGURIDAD:
 * - Link con firma única (signed URL)
 * - Link con expiración (default: 60 minutos)
 * - EmailVerificationRequest valida firma
 * - Solo usuario autenticado puede verificar
 * - Previene verificación por otros usuarios
 * 
 * @package App\Http\Controllers\Auth
 * @since 1.0
 */
class VerifyEmailController extends Controller
{
    /**
     * Marca el email del usuario como verificado.
     * 
     * Este es un invokable controller (single action controller).
     * EmailVerificationRequest ya valida la firma del link.
     * 
     * Proceso:
     * 1. Verifica si email ya está verificado (evita duplicados)
     * 2. Si no, marca email como verificado
     * 3. Dispara evento Verified (puede enviar email de bienvenida)
     * 4. Redirige a dashboard con parámetro ?verified=1
     * 
     * @param EmailVerificationRequest $request Request con validación de firma
     * @return RedirectResponse Redirige a dashboard con confirmación
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // Si email ya verificado, redirigir directamente
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        // Marcar email como verificado
        if ($request->user()->markEmailAsVerified()) {
            // Disparar evento Verified (puede activar listeners)
            event(new Verified($request->user()));
        }

        // Redirigir a dashboard con parámetro de confirmación
        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
