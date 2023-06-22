<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\UserResource;
use App\Library\Enumerations\Role;
use App\Models\User;
use Tests\TestCase;

class UserResourceTest extends TestCase
{
    /**
     * The array with sensitive keys.
     */
    protected array $sensitiveKeys = [
        'email', 'roles',
    ];

    /** @test */
    public function admin_can_view_sensitive_data_of_others(): void
    {
        [$user, $other] = $this->prepare();

        $this->actingAs($user->assignRole(Role::ADMIN));

        $this->assertKeys(new UserResource($other));
    }

    /** @test */
    public function user_can_view_sensitive_data_of_itself(): void
    {
        [$user] = $this->prepare();

        $this->actingAs($user);

        $this->assertKeys(new UserResource($user));
    }

    /** @test */
    public function user_cant_view_sensitive_data_of_others(): void
    {
        [$user, $other] = $this->prepare();

        $this->actingAs($user);

        $this->assertKeys(new UserResource($other), false);
    }

    /**
     * Prepare the tests.
     */
    private function prepare(): array
    {
        $user = User::factory()->create();

        $other = User::factory()->create();

        return [$user, $other];
    }

    /**
     * Assert that the resource has the sensitive keys or not.
     */
    private function assertKeys(UserResource $resource, bool $assert = true): void
    {
        $data = $resource->toArray(null);

        $method = $assert ? 'assertArrayHasKey' : 'assertArrayNotHasKey';

        foreach ($this->sensitiveKeys as $key) {
            $this->$method($key, $data);
        }
    }
}
