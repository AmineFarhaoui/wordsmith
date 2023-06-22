<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Tests\Feature\TestCase;

class ShowTest extends TestCase
{
    /** @test */
    public function it_show_user(): void
    {
        [$user] = $this->prepare();

        $response = $this->makeShowRequest($user, User::factory()->admin()->create());

        $this->assertResponse($response);
    }

    /**
     * Prepares for tests.
     */
    private function prepare(): array
    {
        $user = User::factory()
            ->withMedia('profile_picture')
            ->create();

        return [$user];
    }
}
