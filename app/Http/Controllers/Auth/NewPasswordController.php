<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

/**
 * =======================================================================
 * CONTROLLER: NUEVA CONTRASEÑA (RECUPERACIÓN)
 * =======================================================================
 *
 * Maneja el proceso de establecer una nueva contraseña después de
 * que el usuario solicitó recuperación por email.
 *
 * RESPONSABILIDADES:
 * - Mostrar formulario de nueva contraseña
 * - Validar token de recuperación
 * - Validar nueva contraseña
 * - Resetear contraseña del usuario
 * - Invalidar token usado
 * - Generar nuevo remember_token
 *
 * FLUJO DE RECUPERACIÓN:
 * 1. Usuario solicita recuperación (PasswordResetLinkController)
 * 2. Recibe email con link + token
 * 3. Hace clic en link (método create() - muestra formulario)
 * 4. Ingresa nueva contraseña
 * 5. Valida token y email (método store())
 * 6. Establece nueva contraseña
 * 7. Redirige a login con mensaje de éxito
 *
 * SEGURIDAD:
 * - Token de un solo uso (se invalida al usar)
 * - Token con expiración (default: 60 minutos)
 * - Hash seguro de contraseña
 * - Regenera remember_token
 * - Valida que email coincida con token
 *
 * @since 1.0
 */
class NewPasswordController extends Controller
{
    /**
     * Muestra el formulario para establecer nueva contraseña.
     *
     * Recibe token y email desde el link del correo.
     *
     * @param  Request  $request  Contiene token y email
     * @return View Vista 'auth.reset-password'
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Procesa el establecimiento de nueva contraseña.
     *
     * Este método:
     * 1. Valida token, email y nueva contraseña
     * 2. Verifica que el token sea válido y no haya expirado
     * 3. Actualiza la contraseña del usuario
     * 4. Regenera el remember_token
     * 5. Dispara evento PasswordReset
     * 6. Invalida el token usado
     * 7. Redirige según resultado
     *
     * @return RedirectResponse Redirige a login si éxito, o back con error
     *
     * @throws \Illuminate\Validation\ValidationException Si validación falla
     */
    public function store(Request $request): RedirectResponse
    {
        // Validar datos del formulario
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Intentar resetear la contraseña
        // Password::reset valida el token y ejecuta el callback si es válido
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                // Actualizar contraseña y regenerar remember_token
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60), // Invalida sesiones anteriores
                ])->save();

                // Disparar evento de password reset
                event(new PasswordReset($user));
            }
        );

        // Redirigir según resultado
        // Si éxito: a login con mensaje de éxito
        // Si error: back con mensaje de error
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
