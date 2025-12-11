<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * =======================================================================
 * CONTROLLER: REGISTRO DE NUEVOS USUARIOS
 * =======================================================================
 *
 * Maneja el proceso completo de registro de nuevos usuarios en el sistema.
 * Incluye validación robusta, creación de cuenta y auto-login.
 *
 * RESPONSABILIDADES:
 * - Mostrar formulario de registro
 * - Validar datos del nuevo usuario
 * - Crear cuenta con valores por defecto
 * - Hash de contraseña
 * - Disparar evento Registered
 * - Auto-login del usuario registrado
 *
 * VALIDACIONES APLICADAS:
 * - Usuario: 3-15 caracteres, único, sin espacios, lowercase
 * - Contraseña: 8+ caracteres, mayúsculas, minúsculas, números, símbolos, no comprometida
 * - Email: formato válido, único, máx 45 caracteres
 * - Nombres/Apellidos: solo letras, máx 15 caracteres
 * - Teléfono: 7-15 dígitos numéricos
 * - Fecha nacimiento: formato fecha válido
 *
 * VALORES POR DEFECTO:
 * - estado: 1 (Activo)
 * - tipo_usuario_id: 3 (Atleta)
 *
 * FLUJO DE REGISTRO:
 * 1. Usuario accede a /register
 * 2. Completa formulario
 * 3. Validación de datos
 * 4. Creación de usuario con hash de contraseña
 * 5. Evento Registered (puede enviar email de verificación)
 * 6. Auto-login
 * 7. Redirección a dashboard
 *
 * @since 1.0
 */
class RegisteredUserController extends Controller
{
    /**
     * Muestra el formulario de registro.
     *
     * @return View Vista 'auth.register'
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Procesa el registro de un nuevo usuario.
     *
     * Este método:
     * 1. Valida todos los campos del formulario
     * 2. Crea el usuario con contraseña hasheada
     * 3. Asigna valores por defecto (estado activo, rol Atleta)
     * 4. Dispara evento Registered
     * 5. Hace login automático
     * 6. Redirige al dashboard
     *
     * @return RedirectResponse Redirige a dashboard
     *
     * @throws \Illuminate\Validation\ValidationException Si validación falla
     */
    public function store(Request $request): RedirectResponse
    {
        // Validar todos los campos del formulario
        $request->validate([
            'usuario' => ['required', 'string', 'lowercase', 'between:3,15', 'unique:usuarios', 'regex:/^\S*$/'],
            'contrasena' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(), // Verifica contra base de datos de contraseñas filtradas
            ],
            'correo' => ['required', 'string', 'lowercase', 'email', 'max:45', 'unique:usuarios', 'before_or_equal:'.now()->subYears(8)->format('Y-m-d')],
            'nombre_1' => ['required', 'string', 'alpha', 'max:15'],
            'nombre_2' => ['nullable', 'string', 'alpha', 'max:15'],
            'apellido_1' => ['required', 'string', 'alpha', 'max:15'],
            'apellido_2' => ['nullable', 'string', 'alpha', 'max:15'],
            'telefono' => ['required', 'numeric', 'digits_between:7,15'],
            'fecha_nacimiento' => ['required', 'date'],
        ]);

        // Crear nuevo usuario con datos validados
        $user = User::create([
            'usuario' => $request->usuario,
            'contrasena' => Hash::make($request->contrasena), // Hash seguro de contraseña
            'correo' => $request->correo,
            'nombre_1' => $request->nombre_1,
            'nombre_2' => $request->nombre_2,
            'apellido_1' => $request->apellido_1,
            'apellido_2' => $request->apellido_2,
            'telefono' => $request->telefono,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'estado' => 1,              // 1 = Usuario activo
            'tipo_usuario_id' => 3,     // 3 = Rol Atleta (usuario regular)
        ]);

        // Disparar evento de registro (puede enviar email de verificación)
        event(new Registered($user));

        // Hacer login automático del usuario recién registrado
        Auth::login($user);

        // Redirigir al dashboard
        return redirect(route('dashboard', absolute: false));
    }
}
