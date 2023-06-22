<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    private $oldPassword = 'Password1!';

    private $newPassword = 'Different1!';

    /** @test */
    public function user_can_update_itself(): void
    {
        [$user] = $this->prepare();

        $data = $this->requestData($user);

        $response = $this->makeRequest($user, $data);

        $this->assertResponse($response);

        $this->assertDatabase($user, $data);
    }

    /** @test */
    public function user_cannot_update_password_with_wrong_current_password(): void
    {
        [$user] = $this->prepare();

        $data = $this->requestData($user);

        $data['current_password'] = 'wrong';

        $response = $this->makeRequest($user, $data);

        $this->assertResponse($response, 422);
    }

    /** @test */
    public function user_cannot_update_other_user(): void
    {
        [$user, $other] = $this->prepare();

        $data = $this->requestData($other);

        $response = $this->makeRequest($user, $data, 'users/'.$other->id);

        $this->assertResponse($response, 403);
    }

    /** @test */
    public function user_can_update_media(): void
    {
        [$user] = $this->prepare();

        $data = $this->requestData($user) + [
            'profile_picture' => $this->base64Image,
        ];

        $response = $this->makeRequest($user, $data);

        $this->assertResponse($response);

        $this->assertDatabaseHas('media', [
            'model_type' => $user->getMorphClass(),
            'model_id' => $user->id,
            'collection_name' => 'profile_picture',
        ]);
    }

    /**
     * Prepares for tests.
     */
    private function prepare(): array
    {
        $user = User::factory()->create();

        $other = User::factory()->create();

        return [$user, $other];
    }

    /**
     * Returns data used in a request.
     */
    private function requestData(User $user): array
    {
        $data = User::factory()
            ->make()
            ->only(['email', 'first_name', 'last_name']);

        $data['password'] = $this->newPassword;

        $data['password_confirmation'] = $this->newPassword;

        $data['current_password'] = $this->oldPassword;

        return $data;
    }

    /**
     * Makes a request.
     */
    private function makeRequest(User $user, array $data, string $url = 'users/me'): TestResponse
    {
        return $this->actingAs($user)
            ->json('PATCH', $url, $data);
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
     * Asserts the database.
     */
    private function assertDatabase(User $user, array $data): void
    {
        $whereUser = Arr::only($data, [
            'first_name', 'last_name', 'email',
        ]);

        $whereUser = Arr::add($whereUser, 'id', $user->id);

        $this->assertDatabaseHas('users', $whereUser);

        // Assert that the saved hashed password is correct.
        $this->assertTrue(
            Hash::check($data['password'], $user->password),
            'The saved hashed password is not correct.',
        );
    }
}
