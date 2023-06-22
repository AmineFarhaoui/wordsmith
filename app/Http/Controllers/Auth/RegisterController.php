<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\LoginResource;
use App\Library\Services\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Handle a registration request for the application.
     */
    public function __invoke(RegisterRequest $request, UserService $userService): JsonResponse
    {
        $user = $userService->create($request->validated());

        Auth::login($user);

        $resourceData = [
            'api_token' => Auth::fromUser($user),
            'user' => $user->load('roles'),
        ];

        event(new Registered($user));

        return created(new LoginResource($resourceData));
    }
}
