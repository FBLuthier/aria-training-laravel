<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * =======================================================================
 * CONTROLLER: ACTUALIZACIÓN DE CONTRASEÑA
 * =======================================================================
 *
 * Maneja la actualización de contraseña del usuario autenticado
 * desde su perfil. Requiere contraseña actual para confirmar cambio.
 *
 * RESPONSABILIDADES:
 * - Validar contraseña actual del usuario
 * - Validar nueva contraseña con reglas de seguridad
 * - Hashear y guardar nueva contraseña
 * - Retornar confirmación
 *
 * VALIDACIONES:
 * - Contraseña actual: requerida, debe coincidir con la actual
 * - Nueva contraseña: requerida, cumple reglas de seguridad, confirmada
 *
 * REGLAS DE SEGURIDAD (Password::defaults()):
 * - Mínimo 8 caracteres
 * - Letras y números
 * - Mayúsculas y minúsculas (si configurado)
 * - Caracteres especiales (si configurado)
 *
 * SEGURIDAD:
 * - Requiere autenticación
 * - Verifica contraseña actual
 * - Hash seguro con bcrypt
 * - Error bag separado ('updatePassword')
 *
 * @since 1.0
 */
class PasswordController extends Controller
{
    /**
     * Actualiza la contraseña del usuario autenticado.
     *
     * Proceso:
     * 1. Valida contraseña actual
     * 2. Valida nueva contraseña (longitud, confirmación)
     * 3. Hashea nueva contraseña
     * 4. Actualiza en base de datos
     * 5. Retorna con mensaje de éxito
     *
     * @return RedirectResponse Redirige con mensaje de éxito
     *
     * @throws \Illuminate\Validation\ValidationException Si validación falla
     */
    public function update(Request $request): RedirectResponse
    {
        // Validar con error bag específico para no interferir con otros formularios
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // Actualizar contraseña con hash seguro
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Retornar con mensaje de éxito
        return back()->with('status', 'password-updated');
    }
}
