<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * =======================================================================
 * CONTROLLER: CONFIRMACIÓN DE CONTRASEÑA
 * =======================================================================
 * 
 * Maneja la confirmación de contraseña para acciones sensibles.
 * Requiere que el usuario reingrese su contraseña antes de realizar
 * operaciones críticas (eliminar cuenta, cambiar email, etc.).
 * 
 * RESPONSABILIDADES:
 * - Mostrar formulario de confirmación de contraseña
 * - Validar contraseña del usuario
 * - Marcar sesión como "contraseña confirmada"
 * - Redirigir a acción prevista
 * 
 * USO TÍPICO:
 * Este controller se usa con el middleware 'password.confirm' que
 * redirige aquí si la contraseña no ha sido confirmada recientemente.
 * 
 * Ejemplo de rutas protegidas:
 * - Eliminar cuenta
 * - Cambiar email
 * - Ver información sensible
 * - Cambiar configuración de seguridad
 * 
 * FLUJO:
 * 1. Usuario intenta acción sensible
 * 2. Middleware detecta que necesita confirmación
 * 3. Redirige a /confirm-password (método show())
 * 4. Usuario ingresa contraseña actual
 * 5. Valida contraseña (método store())
 * 6. Marca sesión como confirmada (válido por 3 horas default)
 * 7. Redirige a URL prevista
 * 
 * SEGURIDAD:
 * - Requiere autenticación previa
 * - Validación contra contraseña actual
 * - Confirmación expira después de tiempo configurado
 * - Previene acciones no autorizadas
 * 
 * @package App\Http\Controllers\Auth
 * @since 1.0
 */
class ConfirmablePasswordController extends Controller
{
    /**
     * Muestra el formulario de confirmación de contraseña.
     * 
     * @return View Vista 'auth.confirm-password'
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Confirma la contraseña del usuario.
     * 
     * Este método:
     * 1. Valida la contraseña ingresada contra la actual
     * 2. Si válida: marca timestamp en sesión
     * 3. Si inválida: lanza ValidationException
     * 4. Redirige a URL prevista o dashboard
     * 
     * El timestamp se guarda en: session('auth.password_confirmed_at')
     * El middleware 'password.confirm' verifica este valor.
     * 
     * @param Request $request
     * @return RedirectResponse Redirige a URL prevista
     * @throws ValidationException Si contraseña incorrecta
     */
    public function store(Request $request): RedirectResponse
    {
        // Validar contraseña contra la actual del usuario
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            // Lanzar excepción si contraseña incorrecta
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        // Marcar timestamp de confirmación en sesión
        $request->session()->put('auth.password_confirmed_at', time());

        // Redirigir a URL prevista o dashboard
        return redirect()->intended(route('dashboard', absolute: false));
    }
}
