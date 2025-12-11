<?php

namespace App\Providers;

use App\Models\AuditLog;
use App\Models\Equipo;
use App\Policies\AuditLogPolicy;
use App\Policies\EquipoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Equipo::class => EquipoPolicy::class,
        AuditLog::class => AuditLogPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
