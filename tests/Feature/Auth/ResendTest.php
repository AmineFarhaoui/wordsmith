<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ResendTest extends TestCase
{
    /** @test */
    public function can_resend()
    {
        [$user] = $this->prepare();

        $response = $this->makeRequest($user);

        $this->assertResponse($response);

        $this->assertNotification($user);
    }

    /** @test */
    public function cannot_resend_verified_user()
    {
        [$user] = $this->prepare();

        // Update user to be verified.
        $user->update([
            'email_verified_at' => now()->subDay(),
        ]);

        $response = $this->makeRequest($user);

        $this->assertResponse($response, 400);
    }

    /**
     * Prepares for tests.
     */
    private function prepare(): array
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        return [$user];
    }

    /**
     * Makes a request.
     */
    private function makeRequest(User $user): TestResponse
    {
        return $this->actingAs($user)
            ->json('GET', 'auth/resend');
    }

    /**
     * Asserts a response.
     */
    private function assertResponse(TestResponse $response, int $status = 204): void
    {
        $response->assertStatus($status);
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
