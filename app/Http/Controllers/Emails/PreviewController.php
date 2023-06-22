<?php

namespace App\Http\Controllers\Emails;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class PreviewController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $email): Response
    {
        $method = Str::camel($email);

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        abort(404);
    }

    /**
     * The email verification preview.
     */
    private function emailVerification(): string
    {
        $user = User::first();

        $notification = new EmailVerificationNotification;

        // $user->notify($notification);

        return $notification->toMail($user)->render();
    }

    /**
     * The reset password preview.
     */
    private function resetPassword(): string
    {
        $user = User::first();

        $notification = new ResetPasswordNotification('some_token');

        // $user->notify($notification);

        return $notification->toMail($user)->render();
    }
}
