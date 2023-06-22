<?php

use App\Http\Controllers\Users\UserController;
use App\Http\Requests\Users\UpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'auth',
    'model' => User::class,
], function () {
    Route::apiResource('users', UserController::class, [
        'only' => ['show', 'update'],
        'requests' => [
            'update' => UpdateRequest::class,
        ],
    ]);
});
