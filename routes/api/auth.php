<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RefreshController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', LoginController::class);

    Route::post('register', RegisterController::class);

    Route::get('refresh', RefreshController::class);

    Route::post('password/forgot', [ForgotPasswordController::class, 'sendResetLinkEmail']);

    Route::post('password/reset', [ResetPasswordController::class, 'reset']);

    Route::middleware('throttle:6,1')->group(function () {
        Route::post('verify', [VerificationController::class, 'verify']);

        Route::get('resend', [VerificationController::class, 'resend'])->middleware('auth');
    });
});
