<?php

use App\Http\Controllers\Companies\Users\IndexController;
use App\Models\Company;
use Illuminate\Support\Facades\Route;

Route::group([
    'model' => Company::class,
    'middleware' => 'auth',
    'prefix' => 'companies',
], function () {
    Route::prefix('{company}')
        ->group(function () {
            Route::prefix('users')
                ->group(function () {
                    Route::get('', IndexController::class);
                });
        });
});
