<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\ConvertCase\ConvertRequestToSnakeCase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ConvertRequestToSnakeCaseTest extends TestCase
{
    /** @test */
    public function it_converts_keys_to_snake_case(): void
    {
        $request = new Request([
            'queryKey' => ['queryValue' => 'shouldStillBeCamel'],
        ]);

        $request->merge([
            'camelCase' => 'shouldStillBeCamel',
        ]);

        $middleware = new ConvertRequestToSnakeCase();

        $middleware->handle($request, function ($request) {
            $this->assertMatchesJsonSnapshot($request->all());
        });
    }

    /** @test */
    public function it_converts_and_overrides_keys_to_snake_case(): void
    {
        $request = new Request();

        $request->merge([
            'first_case' => 'original',
            'firstCase' => 'overwritten',
        ]);

        $middleware = new ConvertRequestToSnakeCase();

        $middleware->handle($request, function ($request) {
            $this->assertMatchesJsonSnapshot($request->all());
        });
    }
}
