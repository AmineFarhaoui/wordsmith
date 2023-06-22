<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class RefreshTest extends TestCase
{
    /** @test */
    public function can_refresh(): void
    {
        [$user] = $this->prepare();

        $token = Auth::fromUser($user);

        $response = $this->makeRequest($token);

        $this->assertResponse($response);

        $this->assertToken($user, $response->json()['apiToken']);
    }

    /**
     * Prepares for tests.
     */
    private function prepare(): array
    {
        $user = User::factory()->create();

        return [$user];
    }

    /**
     * Makes a request.
     */
    private function makeRequest(string $token): TestResponse
    {
        return $this->json('GET', 'auth/refresh', [], [
            'Authorization' => "Bearer $token",
        ]);
    }

    /**
     * Asserts a response.
     */
    private function assertResponse(TestResponse $response, int $status = 200): void
    {
        $response->assertStatus($status);

        if ($status !== 200) {
            return;
        }

        $this->assertJsonStructureSnapshot($response);
    }

    /**
     * Asserts a token that has been returned from the request.
     */
    private function assertToken(User $user, string $token): void
    {
        $auth = Auth::setToken($token);

        $this->assertEquals(
            $user->id,
            $auth->user()->id,
            'The token does not belong to the user.',
        );
    }
}
