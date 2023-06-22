<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /** @test */
    public function can_register()
    {
        $data = $this->requestData();

        $response = $this->makeRequest($data);

        $this->assertResponse($response);

        $user = $this->assertDatabase($data);

        $this->assertNotification($user);
    }

    /**
     * Returns the data used in requests.
     */
    private function requestData(string $token = null): array
    {
        $data = User::factory()
            ->make()
            ->only(['email', 'first_name', 'last_name']);

        return array_merge($data, [
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);
    }

    /**
     * Makes a request.
     */
    private function makeRequest(array $data): TestResponse
    {
        return $this->json('POST', 'auth/register', $data);
    }

    /**
     * Asserts the response.
     */
    private function assertResponse(TestResponse $response, int $status = 201): void
    {
        $response->assertStatus($status);

        $this->assertJsonStructureSnapshot($response);
    }

    /**
     * Asserts the database.
     */
    private function assertDatabase(array $data): User
    {
        $userData = Arr::only($data, [
            'email', 'name',
        ]);

        $this->assertDatabaseHas('users', $userData);

        // Assert that the saved hashed password is correct.
        $user = User::where($userData)->firstOrFail();

        $this->assertTrue(
            Hash::check($data['password'], $user->password),
            'The saved hashed password is not correct.',
        );

        return $user;
    }

    /**
     * Asserts notification.
     */
    private function assertNotification(User $user): void
    {
        Notification::assertSentTo(
            $user,
            EmailVerificationNotification::class,
            function ($notification, $channels) {
                return in_array('mail', $channels);
            },
        );
    }
}
