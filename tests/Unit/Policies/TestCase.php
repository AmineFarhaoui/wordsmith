<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * The ability to test.
     */
    protected string $ability = '';

    /** @test */
    public function user_can(): void
    {
        $cases = $this->getCases();

        if (empty($cases)) {
            $this->assertTrue(true);

            return;
        }

        foreach ($cases as $args) {
            $this->assertPolicy(...$args);
        }
    }

    /**
     * Assert the policy.
     */
    protected function assertPolicy(User $user, Model $model, bool $assert): void
    {
        $this->actingAs($user);

        $this->assertEquals($assert, Gate::allows($this->ability, $model));
    }

    /**
     * Returns the test cases.
     */
    protected function getCases(): array
    {
        return [];
    }
}
