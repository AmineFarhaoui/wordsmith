<?php

use App\Http\Controllers\Translations\ExportController;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Support\Facades\Route;

Route::get('translations/export', ExportController::class)
    ->name('translations.export')
    ->middleware(ValidateSignature::class);
