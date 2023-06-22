<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\ConvertCase\ConvertResponseToCamelCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tests\TestCase;

class ConvertResponseToCamelCaseTest extends TestCase
{
    /** @test */
    public function handle()
    {
        $request = new Request;

        $middleware = new ConvertResponseToCamelCase;

        $response = $middleware->handle($request, function (Request $request) {
            return new JsonResponse([
                'snake_case' => 'value',
            ]);
        });

        $this->assertEquals(
            (object) ['snakeCase' => 'value'],
            $response->getData(),
        );
    }

    /** @test */
    public function handle_null()
    {
        $request = new Request;

        $middleware = new ConvertResponseToCamelCase;

        $response = $middleware->handle($request, function (Request $request) {
            return new JsonResponse(null);
        });

        $this->assertEquals(
            [],
            $response->getData(),
        );
    }
}
