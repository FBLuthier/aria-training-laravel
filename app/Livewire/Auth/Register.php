<?php

namespace App\Livewire\Auth;

use Livewire\Component;

/**
 * =======================================================================
 * COMPONENTE: REGISTRO DE USUARIOS
 * =======================================================================
 *
 * Componente Livewire para la página de registro de nuevos usuarios.
 * Maneja el formulario de creación de cuentas en el sistema.
 *
 * FUNCIONALIDADES:
 * - Formulario de registro con validación
 * - Creación de nuevos usuarios
 * - Validación de datos en tiempo real
 * - Redirección post-registro
 *
 * CAMPOS DEL FORMULARIO:
 * - Usuario (username único)
 * - Correo electrónico
 * - Contraseña (con confirmación)
 * - Nombres y apellidos
 * - Teléfono (opcional)
 * - Fecha de nacimiento
 *
 * NOTA: Este componente actualmente solo renderiza la vista.
 * La lógica del formulario puede estar implementada en:
 * - La vista Blade con Alpine.js
 * - Un Form Object separado
 * - Controllers de autenticación de Laravel
 *
 * @since 1.0
 */
class Register extends Component
{
    /**
     * Renderiza la vista de registro.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.auth.register');
    }
}
