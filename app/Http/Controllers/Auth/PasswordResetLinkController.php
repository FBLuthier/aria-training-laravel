<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

/**
 * =======================================================================
 * CONTROLLER: SOLICITUD DE LINK DE RECUPERACIÓN DE CONTRASEÑA
 * =======================================================================
 * 
 * Maneja el envío del email con el link para recuperar contraseña.
 * Primer paso del proceso de recuperación de contraseña olvidada.
 * 
 * RESPONSABILIDADES:
 * - Mostrar formulario de "Olvidé mi contraseña"
 * - Validar email ingresado
 * - Generar token de recuperación
 * - Enviar email con link de recuperación
 * - Retornar confirmación de envío
 * 
 * FLUJO DE RECUPERACIÓN:
 * 1. Usuario hace clic en "Olvidé mi contraseña" en login
 * 2. Muestra formulario (método create())
 * 3. Ingresa su email
 * 4. Valida y envía email con link (método store())
 * 5. Usuario recibe email con token
 * 6. Hace clic en link (NewPasswordController)
 * 7. Establece nueva contraseña
 * 
 * SEGURIDAD:
 * - Token único generado por Laravel
 * - Token con expiración (default: 60 minutos)
 * - Token de un solo uso
 * - Email debe existir en sistema
 * - Rate limiting aplicado
 * 
 * @package App\Http\Controllers\Auth
 * @since 1.0
 */
class PasswordResetLinkController extends Controller
{
    /**
     * Muestra el formulario de solicitud de recuperación de contraseña.
     * 
     * @return View Vista 'auth.forgot-password'
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Procesa la solicitud de link de recuperación.
     * 
     * Este método:
     * 1. Valida el email ingresado
     * 2. Genera token único de recuperación
     * 3. Guarda token en tabla password_reset_tokens
     * 4. Envía email con link que incluye el token
     * 5. Retorna mensaje de confirmación o error
     * 
     * El link enviado tiene formato:
     * /reset-password/{token}?email={email}
     * 
     * @param Request $request
     * @return RedirectResponse Redirige con mensaje de estado
     * @throws \Illuminate\Validation\ValidationException Si email inválido
     */
    public function store(Request $request): RedirectResponse
    {
        // Validar formato de email
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Intentar enviar link de recuperación
        // Password::sendResetLink genera token y envía email
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Retornar según resultado
        // Si éxito: mensaje de confirmación
        // Si error: mensaje de error (email no encontrado, etc.)
        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
