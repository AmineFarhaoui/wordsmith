<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function verify(VerifyRequest $request): JsonResponse
    {
        try {
            $user = User::findOrFail($request->id);
        } catch (Exception $e) {
            abort(400, __('auth.verification.invalid'));
        }

        // The verification token should be valid.
        if (! hash_equals(
            (string) $request->verification_token,
            sha1($user->getEmailForVerification()),
        )) {
            abort(400, __('auth.verification.invalid'));
        }

        // Don't verify again.
        if ($user->hasVerifiedEmail()) {
            return no_content();
        }

        // Try to verify. Put this in a separate statement so it might be more
        // clear this should not happen when the user already has verified.
        if ($request->user()->markEmailAsVerified()) {
            return no_content();
        }

        return abort(500, __('auth.verification.failed'));
    }

    /**
     * Resend the email verification notification.
     */
    public function resend(): JsonResponse
    {
        // Do not allow resending when user has already been verified.
        if (current_user()->hasVerifiedEmail()) {
            return bad_request(__('already_verified'));
        }

        current_user()->sendEmailVerificationNotification();

        return no_content();
    }
}
