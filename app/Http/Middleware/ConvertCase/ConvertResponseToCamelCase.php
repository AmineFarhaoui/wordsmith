<?php

namespace App\Http\Middleware\ConvertCase;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConvertResponseToCamelCase
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $response->setData(
                array_keys_convert_case(
                    json_decode($response->content(), true) ?? [],
                    'camel',
                ),
            );
        }

        return $response;
    }
}
