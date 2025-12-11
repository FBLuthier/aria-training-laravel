<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
