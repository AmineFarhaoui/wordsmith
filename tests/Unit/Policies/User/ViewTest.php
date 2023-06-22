<?php

namespace Tests\Unit\Policies\User;

use App\Models\User;
use Tests\Unit\Policies\TestCase;

class ViewTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    protected string $ability = 'view';

    /** @test */
    public function admin_can_view(): void
    {
        [$user] = $this->prepare();

        $this->assertPolicy(
            User::factory()->admin()->create(),
            $user,
            true,
        );
    }

    /** @test */
    public function user_can_view_itself(): void
    {
        [$user] = $this->prepare();

        $this->assertPolicy(
            $user,
            $user,
            true,
        );
    }

    /** @test */
    public function user_cant_view_other(): void
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
