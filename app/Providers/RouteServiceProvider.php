<?php

namespace App\Providers;

use App\Http\Bindings\ModelBinding;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The explicit model bindings.
     */
    protected $explicitBindings = [
        'user' => \App\Models\User::class,
    ];

    /**
     * The parameter model bindings.
     */
    protected $parameterBindings = [
        'model' => ModelBinding::class,
    ];

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        parent::boot();

        $this->bindExplicit();

        $this->bindParameter();

        $this->configureRateLimiting();

        Route::pattern('model', '[A-Za-z-_]+');
    }

    /**
     * Define the routes for the application.
     */
    public function map(): void
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        foreach (glob(base_path('routes/api/').'*.php') as $filename) {
            Route::middleware('api')
                ->group($filename);
        }
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapWebRoutes(): void
    {
        foreach (glob(base_path('routes/web/').'*.php') as $filename) {
            Route::middleware('web')
                ->group($filename);
        }
    }

    /**
     * Creates explicit model bindings.
     */
    protected function bindExplicit(): void
    {
        foreach ($this->explicitBindings as $key => $class) {
            Route::model($key, $class);
        }
    }

    /**
     * Creates parameter model bindings.
     */
    protected function bindParameter(): void
    {
        foreach ($this->parameterBindings as $key => $class) {
            Route::bind($key, $class);
        }
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)
                ->by($request->user()?->id ?: $request->ip());
        });
    }
}
