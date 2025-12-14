<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);

        // Configurar Livewire para subcarpeta XAMPP
        // Esto es necesario cuando APP_URL no tiene el prefijo correcto
        Livewire::setUpdateRoute(function ($handle) {
            return \Illuminate\Support\Facades\Route::post('/livewire/update', $handle)
                ->middleware(['web']);
        });

        // Directivas de Blade para Roles
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->tipo_usuario_id === UserRole::Admin;
        });

        Blade::if('entrenador', function () {
            return auth()->check() && auth()->user()->tipo_usuario_id === UserRole::Entrenador;
        });

        Blade::if('atleta', function () {
            return auth()->check() && auth()->user()->tipo_usuario_id === UserRole::Atleta;
        });
    }
}
