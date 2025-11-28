<?php

use App\Http\Controllers\Admin\AuditoriaController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Admin\GestionarEquipos;
use App\Livewire\Admin\GestionarAuditoria;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí es donde puedes registrar las rutas web para tu aplicación.
| Estas rutas son cargadas por el RouteServiceProvider y todas ellas
| serán asignadas al grupo de middleware "web".
|
*/

// --- RUTA PÚBLICA DE BIENVENIDA ---
Route::get('/', function () {
    return view('welcome');
});

// --- RUTA DEL DASHBOARD PRINCIPAL ---
// Requiere que el usuario esté autenticado y su email verificado.
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// --- GRUPO DE RUTAS PROTEGIDAS POR AUTENTICACIÓN ---
// Todas las rutas dentro de este grupo requieren que el usuario haya iniciado sesión.
Route::middleware('auth')->group(function () {
    
    // Rutas para la gestión del perfil de usuario (proveídas por Laravel Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // --- GRUPO DE RUTAS DE ADMINISTRACIÓN ---
    // Protegido por el middleware 'admin.role', asegurando que solo los administradores puedan acceder.
    // Todas las rutas aquí tendrán el prefijo 'admin/' en la URL y 'admin.' en el nombre de la ruta.
    Route::prefix('admin')->name('admin.')->middleware('admin.role')->group(function () {
    
        // =======================================================================
        //  CRUD DE EQUIPOS (MIGRADO A LIVEWIRE)
        // =======================================================================
        
        /**
         * ESTA ÚNICA LÍNEA AHORA MANEJA TODO EL CRUD DE EQUIPOS.
         * Antes, necesitábamos múltiples rutas (index, create, store, edit, update, destroy,
         * trash, restore, forceDelete) que apuntaban a un controlador.
         * * Ahora, esta ruta simplemente renderiza nuestro componente de Livewire 'GestionarEquipos'.
         * Toda la lógica de mostrar la lista, la papelera, los modales de creación y edición,
         * y todas las acciones, se gestionan internamente dentro de ese componente.
         * Esto simplifica enormemente el mapa de rutas y centraliza la funcionalidad.
         */
        Route::get('equipos', GestionarEquipos::class)->name('equipos.index');

        // =======================================================================
        //  GESTIÓN DE EJERCICIOS (LIVEWIRE)
        // =======================================================================
        Route::get('/ejercicios', \App\Livewire\Admin\GestionarEjercicios::class)->name('ejercicios');
        Route::get('/rutinas', \App\Livewire\Admin\GestionarRutinas::class)->name('rutinas');
        Route::get('/rutinas/{id}/calendario', \App\Livewire\Admin\GestionarRutinaCalendario::class)->name('rutinas.calendario');
        Route::get('/rutinas/dia/{diaId}', \App\Livewire\Admin\GestionarDiaRutina::class)->name('rutinas.dia');

        // =======================================================================
        //  GESTIÓN DE USUARIOS (LIVEWIRE)
        // =======================================================================
        Route::get('usuarios', \App\Livewire\Admin\GestionarUsuarios::class)->name('usuarios.index');

        // =======================================================================
        //  GESTIÓN DE AUDITORÍA
        // =======================================================================

        /**
         * RUTA PARA LA GESTIÓN DE AUDITORÍA.
         * Esta ruta permite a los administradores ver el registro completo de todas las acciones
         * realizadas en el sistema. Incluye funcionalidades de filtrado, búsqueda y visualización
         * detallada de cambios.
         */
        Route::get('auditoria', GestionarAuditoria::class)->name('auditoria.index');

        /**
         * RUTA PARA LA EXPORTACIÓN DE AUDITORÍA.
         * Permite descargar los logs de auditoría en formato CSV según los filtros aplicados.
         */
        Route::get('auditoria/export', [AuditoriaController::class, 'export'])->name('auditoria.export');

    });
    
});

// --- ARCHIVO DE RUTAS DE AUTENTICACIÓN ---
// Incluye las rutas para login, registro, reseteo de contraseña, etc.
require __DIR__.'/auth.php';