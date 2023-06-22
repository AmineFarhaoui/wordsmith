<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\LoginResource;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use ThrottlesLogins;

    /**
     * The max attempts someone can try to login before the throttle kicks in.
     */
    protected int $maxAttempts = 5;

    /**
     * The LoginController constructor.
     */
    public function __construct()
    {
        $this->maxAttempts = config(
            'auth.login_throttle_max_attempts',
            $this->maxAttempts,
        );
    }

    /**
     * Tries to authenticate the user.
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        // Check if the amount of attempts exceeded the configured max.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // Attempt to authenticate, respond with unauthenticated when failed.
        $token = Auth::attempt($request->validated());

        if ($token === false) {
            $this->incrementLoginAttempts($request);

            return unauthenticated(__('auth.failed'));
        }

        // User authenticated, clear login attempts and send response.
        $this->clearLoginAttempts($request);

        return ok(new LoginResource([
            'api_token' => $token,
            'user' => Auth::user()->load('roles'),
        ]));
    }

    /**
     * Get the login username to be used by the controller.
     */
    public function username(): string
    {
        return 'email';
    }
}
