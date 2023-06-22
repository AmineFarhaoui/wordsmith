<?php

use App\Http\Controllers\Projects\Translations\IndexController;
use App\Http\Controllers\Projects\Translations\PullController;
use App\Http\Controllers\Projects\Translations\PushController;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

Route::group([
    'model' => Project::class,
    'middleware' => 'auth',
    'prefix' => 'projects',
], function () {
    Route::prefix('{project}')
        ->group(function () {
            Route::get('translations', IndexController::class);
        });
});

Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'projects',
], function () {
    Route::prefix('{project}')
        ->group(function () {
            Route::prefix('translations')
                ->group(function () {
                    Route::get('pull', PullController::class);
                    Route::post('push', PushController::class);
                });
        });
});
