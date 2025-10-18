<?php

namespace App\Http\Controllers;


use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * =======================================================================
 * CONTROLLER: PERFIL DE USUARIO
 * =======================================================================
 * 
 * Maneja todas las operaciones relacionadas con el perfil del usuario
 * autenticado: visualización, actualización y eliminación de cuenta.
 * 
 * RESPONSABILIDADES:
 * - Mostrar formulario de edición de perfil
 * - Actualizar información del usuario
 * - Eliminar cuenta del usuario
 * - Gestionar verificación de email al cambiar
 * 
 * RUTAS ASOCIADAS:
 * - GET /profile - Formulario de edición
 * - PATCH /profile - Actualizar datos
 * - DELETE /profile - Eliminar cuenta
 * 
 * SEGURIDAD:
 * - Requiere autenticación
 * - Validación de contraseña para eliminación
 * - Invalidación de sesión al eliminar cuenta
 * - Regeneración de token CSRF
 * 
 * @package App\Http\Controllers
 * @since 1.0
 */
class ProfileController extends Controller
{
    /**
     * Muestra el formulario para editar el perfil del usuario.
     *
     * @param  Request  $request La petición HTTP actual.
     * @return View La vista 'profile.edit' con los datos del usuario.
     */
    public function edit(Request $request): View
    {
        // Retorna la vista 'profile.edit' y le pasa la información
        // del usuario actualmente autenticado para que pueda ser mostrada en el formulario.
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Actualiza la información del perfil del usuario.
     *
     * @param  ProfileUpdateRequest  $request Petición personalizada que ya contiene la validación de los datos.
     * @return RedirectResponse Redirige de vuelta a la página de edición de perfil.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // 1. Rellena el modelo del usuario con los datos que ya han sido validados
        // por la clase `ProfileUpdateRequest`.
        $request->user()->fill($request->validated());

        // 2. Comprueba si el campo 'email' ha sido modificado.
        // El método `isDirty()` verifica si un atributo ha cambiado desde la última vez que se guardó.
        if ($request->user()->isDirty('email')) {
            // Si el email cambió, se anula la fecha de verificación del email.
            // Esto obliga al usuario a verificar su nueva dirección de correo.
            $request->user()->email_verified_at = null;
        }

        // 3. Guarda los cambios en la base de datos.
        $request->user()->save();

        // 4. Redirige al usuario de vuelta a la página de edición de perfil
        // con un mensaje de estado en la sesión para mostrar una notificación de éxito.
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Elimina permanentemente la cuenta del usuario.
     *
     * @param  Request  $request La petición HTTP actual.
     * @return RedirectResponse Redirige a la página de inicio.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // 1. Valida la petición para asegurarse de que la contraseña proporcionada es correcta.
        // `validateWithBag('userDeletion', ...)` asegura que si hay un error,
        // se guarde en un "error bag" específico, para no interferir con otros formularios en la página.
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        // 2. Guarda una referencia al usuario actual antes de invalidar la sesión.
        $user = $request->user();

        // 3. Cierra la sesión del usuario.
        Auth::logout();

        // 4. Elimina el registro del usuario de la base de datos.
        $user->delete();

        // 5. Invalida la sesión actual para prevenir ataques de "session fixation".
        $request->session()->invalidate();
        // 6. Regenera el token de la sesión para mayor seguridad.
        $request->session()->regenerateToken();

        // 7. Redirige al usuario a la página de inicio ('/').
        return Redirect::to('/');
    }
}