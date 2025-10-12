<?php

namespace App\Http\Requests\Auth;

// --- IMPORTACIONES DE CLASES ---
use Illuminate\Auth\Events\Lockout;          // Evento que se dispara cuando un usuario es bloqueado.
use Illuminate\Foundation\Http\FormRequest; // Clase base para las peticiones de formulario.
use Illuminate\Support\Facades\Auth;        // Fachada para interactuar con el sistema de autenticación.
use Illuminate\Support\Facades\RateLimiter; // Fachada para manejar el límite de intentos (seguridad).
use Illuminate\Support\Str;                   // Helper para manipulación de strings.
use Illuminate\Validation\ValidationException;// Excepción que se lanza cuando la validación falla.

/**
 * =========================================================================
 * PETICIÓN DE FORMULARIO PARA EL INICIO DE SESIÓN
 * =========================================================================
 * Esta clase tiene tres responsabilidades principales:
 * 1. Validar los campos del formulario de login ('usuario' y 'contrasena').
 * 2. Intentar autenticar al usuario con las credenciales proporcionadas.
 * 3. Implementar un sistema de seguridad (Rate Limiting) para bloquear
 * intentos de inicio de sesión por fuerza bruta.
 */
class LoginRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta petición.
     * En un formulario de login, cualquier usuario (incluso no autenticado)
     * puede intentar iniciar sesión, por lo que siempre devolvemos `true`.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define las reglas de validación que aplican a la petición.
     * Estos son los campos que se esperan del formulario de login.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // El campo 'usuario' es obligatorio y debe ser texto.
            'usuario' => ['required', 'string'],
            // El campo 'contrasena' es obligatorio y debe ser texto.
            'contrasena' => ['required', 'string'],
        ];
    }

    /**
     * Intenta autenticar las credenciales de la petición.
     * Este método se llama desde el controlador después de que la validación de `rules()` haya pasado.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        // 1. Primero, nos aseguramos de que el usuario no esté bloqueado por demasiados intentos.
        $this->ensureIsNotRateLimited();

        // 2. Intentamos iniciar sesión con las credenciales proporcionadas.
        // Se mapean los campos del formulario ('usuario', 'contrasena') a los que Auth espera ('usuario', 'password').
        // El segundo parámetro, `$this->boolean('remember')`, gestiona la casilla "Recordarme".
        if (! Auth::attempt(['usuario' => $this->usuario, 'password' => $this->contrasena], $this->boolean('remember'))) {
            
            // 3. SI LA AUTENTICACIÓN FALLA:
            //    a) Registramos un intento fallido para este usuario/IP.
            RateLimiter::hit($this->throttleKey());

            //    b) Lanzamos una excepción de validación con un mensaje de error genérico.
            //       Esto evita dar pistas a atacantes sobre si el usuario o la contraseña son incorrectos.
            throw ValidationException::withMessages([
                'usuario' => trans('auth.failed'),
            ]);
        }

        // 4. SI LA AUTENTICACIÓN TIENE ÉXITO:
        //    Limpiamos el contador de intentos fallidos para este usuario/IP.
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Asegura que la petición de login no esté bloqueada por límite de intentos.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        // 1. Comprueba si se han superado los 5 intentos permitidos para la clave de throttle.
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            // Si no se ha superado el límite, no hacemos nada y continuamos.
            return;
        }

        // 2. SI EL LÍMITE SE HA SUPERADO:
        //    a) Disparamos el evento 'Lockout' para que otros listeners puedan reaccionar si es necesario.
        event(new Lockout($this));

        //    b) Obtenemos cuántos segundos faltan para que el bloqueo expire.
        $seconds = RateLimiter::availableIn($this->throttleKey());

        //    c) Lanzamos una excepción de validación con el mensaje de bloqueo.
        throw ValidationException::withMessages([
            // NOTA: El error se muestra en el campo 'usuario' para dar feedback al usuario correcto.
            'usuario' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Obtiene la clave única para el límite de intentos de esta petición.
     * La clave se genera combinando el email/usuario en minúsculas y la dirección IP.
     *
     * @return string
     */
    public function throttleKey(): string
    {
        // 🐛 PUNTO DE ATENCIÓN Y CORRECCIÓN:
        // El código original usaba `$this->string('email')`, pero tu formulario usa 'usuario'.
        // Para que el Rate Limiter funcione correctamente, debe usar el identificador de login del formulario.
        // Lo hemos corregido a `$this->string('usuario')`.
        return Str::transliterate(Str::lower($this->string('usuario')).'|'.$this->ip());
    }
}