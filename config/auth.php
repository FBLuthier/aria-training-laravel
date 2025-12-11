<?php

/**
 * =======================================================================
 * CONFIGURACIÓN: AUTENTICACIÓN
 * =======================================================================
 *
 * Configuración del sistema de autenticación de Laravel.
 * Define cómo los usuarios se autentican y recuperan contraseñas.
 *
 * ESTRUCTURA DEL PROYECTO:
 * - Guard: 'web' (sesión estándar basada en cookies)
 * - Provider: 'users' (modelo App\Models\User con tabla 'usuarios')
 * - Tabla de usuarios: 'usuarios' (no 'users')
 * - Tabla de tokens: 'password_reset_tokens'
 *
 * GUARDS (GUARDIAS):
 * Define cómo se autentica el usuario:
 * - 'web': Sesión basada en cookies (default para apps web)
 * - Podríamos agregar 'api' para APIs con tokens
 *
 * PROVIDERS (PROVEEDORES):
 * Define dónde se buscan los usuarios:
 * - 'users': Usa modelo User con Eloquent
 * - Tabla real: 'usuarios' (definida en el modelo)
 *
 * PASSWORD RESET (RECUPERACIÓN):
 * - Tokens expiran en 60 minutos
 * - Throttle: 60 segundos entre intentos
 * - Tabla: password_reset_tokens
 *
 * PASSWORD CONFIRMATION (CONFIRMACIÓN):
 * - Timeout: 10800 segundos (3 horas)
 * - Usado para acciones sensibles (eliminar cuenta, etc.)
 *
 * @since 1.0
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option defines the default authentication "guard" and password
    | reset "broker" for your application. You may change these values
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | which utilizes session storage plus the Eloquent user provider.
    |
    | All authentication guards have a user provider, which defines how the
    | users are actually retrieved out of your database or other storage
    | system used by the application. Typically, Eloquent is utilized.
    |
    | Supported: "session"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication guards have a user provider, which defines how the
    | users are actually retrieved out of your database or other storage
    | system used by the application. Typically, Eloquent is utilized.
    |
    | If you have multiple user tables or models you may configure multiple
    | providers to represent the model / table. These providers may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | These configuration options specify the behavior of Laravel's password
    | reset functionality, including the table utilized for token storage
    | and the user provider that is invoked to actually retrieve users.
    |
    | The expiry time is the number of minutes that each reset token will be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    | The throttle setting is the number of seconds a user must wait before
    | generating more password reset tokens. This prevents the user from
    | quickly generating a very large amount of password reset tokens.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the number of seconds before a password confirmation
    | window expires and users are asked to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
