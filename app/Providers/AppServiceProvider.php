<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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
        Schema::defaultStringLength(191);
        if (env('APP_ENV') !== 'local') { // Solo forzar en Heroku/producción
            URL::forceScheme('https');
        }

        Gate::define('access-filament', function (?User $user) {
            // Permite el acceso a cualquier usuario con el rol 'Administrador'.
            // El chequeo ahora es seguro ante usuarios nulos (visitantes/guests).
            return $user && $user->hasRole('Administrador');
        });
    }
}
