<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EquipoController;
use App\Livewire\Admin\GestionarEquipos; // <-- INICIO: 1. Importamos el componente Livewire

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::prefix('admin')->name('admin.')->middleware('admin.role')->group(function () {
    
        // --- INICIO: 2. Cambios para la migración a Livewire ---

        Route::get('equipos', \App\Livewire\Admin\GestionarEquipos::class)->name('equipos.index');


        // El resto de las rutas del CRUD (create, store, edit, etc.) siguen usando el controlador,
        // pero excluimos 'index' para evitar conflictos.
        Route::resource('equipos', EquipoController::class)->except(['index']);

        // --- FIN: 2. Cambios para la migración a Livewire ---

        // Las rutas de la papelera no cambian
        Route::get('equipos/trash', [EquipoController::class, 'trash'])->name('equipos.trash');
        Route::put('equipos/{equipo}/restore', [EquipoController::class, 'restore'])->name('equipos.restore');
        Route::delete('equipos/{equipo}/force-delete', [EquipoController::class, 'forceDelete'])->name('equipos.forceDelete');
    });
    
});

require __DIR__.'/auth.php';