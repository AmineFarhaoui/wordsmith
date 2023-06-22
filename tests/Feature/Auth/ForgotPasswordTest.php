<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    /** @test */
    public function can_forgot_password()
    {
        [$user] = $this->prepare();

        $data = $this->requestData($user);

        $response = $this->makeRequest($data);

        $this->assertResponse($response);

        $this->assertDatabase($user);

        $this->assertNotification($user);
    }

    /** @test */
    public function cannot_forgot_password_non_existing_email()
    {
        [$user] = $this->prepare();

        $data = $this->requestData($user);

        // Change the email to non existing.
        $data['email'] = 'non@existing.com';

        $response = $this->makeRequest($data);

        $this->assertResponse($response, 422);
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
     * Returns the data used in requests.
     */
    private function requestData(User $user): array
    {
        return [
            'email' => $user->email,
        ];
    }

    /**
     * Makes a request.
     */
    private function makeRequest(array $data): TestResponse
    {
        return $this->json('POST', 'auth/password/forgot', $data);
    }

    /**
     * Asserts the response.
     */
    private function assertResponse(TestResponse $response, int $status = 204): void
    {
        $response->assertStatus($status);
    }

    /**
     * Asserts the database.
     */
    private function assertDatabase(User $user): void
    {
        $this->assertDatabaseHas('password_resets', [
            'email' => $user->email,
        ]);
    }

    /**
     * Asserts notification.
     */
    private function assertNotification(User $user): void
    {
        Notification::assertSentTo(
            $user,
            ResetPasswordNotification::class,
            function ($notification, $channels) {
                return in_array('mail', $channels);
            },
        );
    }
}
