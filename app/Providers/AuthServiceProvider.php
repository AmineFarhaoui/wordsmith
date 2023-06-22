<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Auto discover policies.
        Gate::guessPolicyNamesUsing(function ($modelClass) {
            return sprintf(
                'App\\Policies\\%sPolicy',
                class_basename($modelClass),
            );
        });
    }
}
