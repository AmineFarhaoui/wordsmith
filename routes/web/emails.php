<?php

use App\Http\Controllers\Emails\PreviewController;

if (app()->environment('production')) {
    return;
}

Route::get('emails/preview/{email}', PreviewController::class);
