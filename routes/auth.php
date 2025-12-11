<?php

// --- IMPORTACIONES DE CLASES ---
// Se importan todos los controladores que manejan la lógica de autenticación.
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas de Autenticación
|--------------------------------------------------------------------------
|
| Este archivo contiene todas las rutas relacionadas con el proceso de
| autenticación de usuarios, como el registro, inicio de sesión,
| recuperación de contraseña, verificación de email y cierre de sesión.
|
*/

// =========================================================================
//  GRUPO DE RUTAS PARA INVITADOS (USUARIOS NO AUTENTICADOS)
// =========================================================================
// El middleware 'guest' asegura que estas rutas solo sean accesibles
// para usuarios que NO han iniciado sesión. Si un usuario autenticado
// intenta acceder a ellas, será redirigido al dashboard.
Route::middleware('guest')->group(function () {

    // --- Registro de Nuevos Usuarios ---
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register'); // Muestra el formulario de registro.
    Route::post('register', [RegisteredUserController::class, 'store']); // Procesa los datos del formulario de registro.

    // --- Inicio de Sesión ---
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login'); // Muestra el formulario de inicio de sesión.
    Route::post('login', [AuthenticatedSessionController::class, 'store']); // Procesa las credenciales e intenta iniciar sesión.

    // --- Recuperación de Contraseña (Paso 1: Solicitar Enlace) ---
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request'); // Muestra el formulario para solicitar el enlace de reseteo.
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email'); // Procesa el email y envía el enlace.

    // --- Recuperación de Contraseña (Paso 2: Resetear Contraseña) ---
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset'); // Muestra el formulario para establecer una nueva contraseña (usa el token del enlace).
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store'); // Procesa y guarda la nueva contraseña.
});

// =========================================================================
//  GRUPO DE RUTAS PARA USUARIOS AUTENTICADOS
// =========================================================================
// El middleware 'auth' asegura que estas rutas solo sean accesibles
// para usuarios que SÍ han iniciado sesión.
Route::middleware('auth')->group(function () {

    // --- Verificación de Email ---
    // Muestra una página pidiendo al usuario que verifique su email si aún no lo ha hecho.
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');

    // La ruta a la que apunta el enlace en el correo de verificación.
    // 'signed': Asegura que la URL no haya sido manipulada.
    // 'throttle:6,1': Limita el acceso a esta ruta a 6 veces por minuto para evitar abusos.
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // Permite al usuario solicitar que se le reenvíe el correo de verificación.
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // --- Confirmación de Contraseña ---
    // Muestra un formulario para que el usuario vuelva a introducir su contraseña antes de realizar una acción sensible.
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']); // Procesa la confirmación de la contraseña.

    // --- Actualización de Contraseña ---
    // Procesa la actualización de la contraseña del usuario desde el formulario de perfil.
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // --- Cierre de Sesión ---
    // Invalida la sesión actual del usuario y lo redirige a la página de inicio.
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
