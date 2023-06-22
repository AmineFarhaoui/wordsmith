<?php

namespace Tests\Unit\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class ResolveRouteBindingTest extends TestCase
{
    /** @test */
    public function primary_key()
    {
        [$user] = $this->prepare();

        $result = $user->resolveRouteBinding($user->getKey());

        $this->assertTrue($result->is($user));
    }

    /** @test */
    public function not_found_primary_key()
    {
        [$user] = $this->prepare();

        // Expect an exception to be thrown.
        $this->expectException(ModelNotFoundException::class);

        // Resolve unknown primary key
        $user->resolveRouteBinding(0);
    }

    /** @test */
    public function me()
    {
        [$user] = $this->prepare();

        // Authenticate user.
        Auth::login($user);

        // Resolve me.
        $result = $user->resolveRouteBinding('me');

        $this->assertTrue($result->is($user));
    }

    /** @test */
    public function unauthenticated_me()
    {
        [$user] = $this->prepare();

        // Do not authenticate user and expect an exception to be thrown.
        $this->expectException(HttpException::class);

        // Resolve me.
        $user->resolveRouteBinding('me');
    }

    /**
     * Prepares for tests.
     */
    private function prepare(): array
    {
        $user = User::factory()->create();

        return [$user];
    }
}
