<?php

namespace App\Http\Requests;

// --- IMPORTACIONES DE CLASES ---
use App\Models\User;                        // Se importa el modelo User para poder interactuar con la tabla de usuarios.
use Illuminate\Foundation\Http\FormRequest; // Clase base para todas las peticiones de formulario.
use Illuminate\Validation\Rule;             // Helper para construir reglas de validación avanzadas.

/**
 * =========================================================================
 * PETICIÓN DE FORMULARIO PARA LA ACTUALIZACIÓN DEL PERFIL
 * =========================================================================
 * Esta clase se encarga exclusivamente de validar los datos enviados desde
 * el formulario de edición de perfil.
 *
 * Al usar un FormRequest, separamos la lógica de validación del controlador,
 * lo que resulta en un código más limpio y reutilizable. Si la validación falla,
 * Laravel automáticamente redirige al usuario a la página anterior con los
 * mensajes de error, sin que el código del controlador llegue a ejecutarse.
 */
class ProfileUpdateRequest extends FormRequest
{
    /**
     * Define las reglas de validación que aplican a la petición.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Reglas para el campo 'name':
            'name' => [
                'required', // El nombre es obligatorio.
                'string',   // Debe ser una cadena de texto.
                'max:255',  // No puede exceder los 255 caracteres.
            ],
            
            // Reglas para el campo 'email':
            'email' => [
                'required',  // El email es obligatorio.
                'string',    // Debe ser una cadena de texto.
                'lowercase', // Convierte automáticamente el email a minúsculas antes de validar.
                'email',     // Debe tener un formato de dirección de email válido.
                'max:255',   // No puede exceder los 255 caracteres.

                // --- REGLA DE VALIDACIÓN CLAVE ---
                // Esta es la regla más importante aquí.
                // 1. `Rule::unique(User::class)`: Le dice a Laravel que verifique que el valor
                //    de este campo ('email') sea único en la tabla asociada al modelo `User`.
                //
                // 2. `->ignore($this->user()->id)`: Le dice a la regla `unique` que ignore un registro
                //    específico durante la verificación. En este caso, ignora el registro del
                //    propio usuario que está actualizando su perfil.
                //
                // ¿Por qué es crucial? Sin `ignore()`, si un usuario intenta guardar su perfil
                // sin cambiar su email, la validación fallaría diciendo "el email ya existe",
                // porque lo encontraría en su propio registro. Con `ignore()`, la validación
                // solo fallará si el email pertenece a OTRO usuario.
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }
}