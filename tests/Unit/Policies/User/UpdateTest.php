<?php

namespace Tests\Unit\Policies\User;

use App\Models\User;
use Tests\Unit\Policies\TestCase;

class UpdateTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    protected string $ability = 'update';

    /** @test */
    public function admin_can_update(): void
    {
        [$user] = $this->prepare();

        $this->assertPolicy(
            User::factory()->admin()->create(),
            $user,
            true,
        );
    }

    /** @test */
    public function user_can_update_itself(): void
    {
        [$user] = $this->prepare();

        $this->assertPolicy(
            $user,
            $user,
            true,
        );
    }

    /** @test */
    public function user_cant_update_other(): void
    {
        [$user] = $this->prepare();

        $this->assertPolicy(
            User::factory()->create(),
            $user,
            false,
        );
    }

    /**
     * Prepare the test.
     */
    private function prepare(): array
    {
        $user = User::factory()->create();

        return [$user];
    }
}
