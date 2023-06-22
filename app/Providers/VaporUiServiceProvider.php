<?php

namespace App\Providers;

use App\Library\Enumerations\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class VaporUiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->gate();
    }

    /**
     * Register the Vapor UI gate.
     *
     * This gate determines who can access Vapor UI in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewVaporUI', function (User $user = null) {
            return Auth::guard('web')->user()?->hasRole(Role::ADMIN);
        });
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
