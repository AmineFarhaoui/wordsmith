<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    /**
     * The new password.
     *
     * @var string
     */
    private $password = 'Password1!';

    /** @test */
    public function can_reset_password()
    {
        [$user, $token] = $this->prepare();

        $data = $this->requestData($user, $token);

        $response = $this->makeRequest($data);

        $this->assertResponse($response);

        $this->assertDatabase($user);
    }

    /** @test */
    public function cannot_reset_password_non_existing_email()
    {
        [$user, $token] = $this->prepare();

        $data = $this->requestData($user, $token);

        // Change the email to non existing.
        $data['email'] = 'non@existing.com';

        $response = $this->makeRequest($data);

        $this->assertResponse($response, 422);
    }

    /** @test */
    public function cannot_reset_password_non_existing_token()
    {
        [$user, $token] = $this->prepare();

        // Change the token to non existing.
        $token = 'non_existing_token';

        $data = $this->requestData($user, $token);

        $response = $this->makeRequest($data);

        $this->assertResponse($response, 422);
    }

    /**
     * Prepares for tests.
     */
    private function prepare(): array
    {
        $user = User::factory()->create();

        // Request a password reset and retrieve the token.
        $this->post('/auth/password/forgot', [
            'email' => $user->email,
        ]);

        $token = null;

        Notification::assertSentTo(
            $user,
            ResetPasswordNotification::class,
            function ($notification, $channels) use (&$token) {
                $token = $notification->token;

                return true;
            },
        );

        return [$user, $token];
    }

    /**
     * Returns the data used in requests.
     */
    private function requestData(User $user, string $token): array
    {
        return [
            'email' => $user->email,
            'password' => $this->password,
            'password_confirmation' => $this->password,
            'token' => $token,
        ];
    }

    /**
     * Makes a request.
     */
    private function makeRequest(array $data): TestResponse
    {
        return $this->json('POST', 'auth/password/reset', $data);
    }

    /**
     * Asserts the response.
     */
    private function assertResponse(TestResponse $response, int $status = 200): void
    {
        $response->assertStatus($status);

        $this->assertJsonStructureSnapshot($response);
    }

    /**
     * Asserts the database.
     */
    private function assertDatabase(User $user): void
    {
        // Assert that the saved hashed password is correct.
        $user = $user->refresh();

        $this->assertTrue(
            Hash::check($this->password, $user->password),
            'The saved hashed password is not correct.',
        );
    }
}
