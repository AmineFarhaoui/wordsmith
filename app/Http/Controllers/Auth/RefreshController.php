<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoginResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RefreshController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): JsonResponse
    {
        return ok(new LoginResource([
            'api_token' => Auth::refresh(),
            'user' => current_user()->load('roles'),
        ]));
    }
}
