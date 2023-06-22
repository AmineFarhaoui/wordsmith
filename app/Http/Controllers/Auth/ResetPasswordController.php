<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoginResource;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Reset the given user's password.
     */
    protected function resetPassword(CanResetPassword $user, string $password): void
    {
        $this->setUserPassword($user, $password);

        // Removed the setting of the remember token.

        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }

    /**
     * Get the response for a successful password reset.
     */
    protected function sendResetResponse(Request $request, string $response): JsonResponse
    {
        $user = Auth::user();

        return ok(new LoginResource([
            'api_token' => Auth::fromUser($user),
            'user' => $user,
        ]));
    }

    /**
     * Get the response for a failed password reset.
     */
    protected function sendResetFailedResponse(Request $request, string $response): JsonResponse
    {
        return unprocessable_entity('', ['email' => [__($response)]]);
    }
}
