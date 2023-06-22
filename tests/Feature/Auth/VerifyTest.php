<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class VerifyTest extends TestCase
{
    /** @test */
    public function can_verify()
    {
        [$user] = $this->prepare();

        $data = $this->requestData($user);

        $response = $this->makeRequest($data);

        $this->assertResponse($response);

        $this->assertDatabase($user);
    }

    /** @test */
    public function cannot_verify_incorrect_user_id()
    {
        [$user] = $this->prepare();

        $data = $this->requestData($user);

        // Create other user ...
        $other = User::factory()->create();

        // ... change id.
        $data['id'] = $other->id;

        $response = $this->makeRequest($data);

        $this->assertResponse($response, 400);
    }

    /** @test */
    public function cannot_verify_incorrect_verification_token()
    {
        [$user] = $this->prepare();

        $data = $this->requestData($user);

        // Change the verification token.
        $data['verification_token'] = 'incorrect_verification_token';

        $response = $this->makeRequest($data);

        $this->assertResponse($response, 400);
    }

    /** @test */
    public function cannot_verify_verified_user()
    {
        [$user] = $this->prepare();

        // Update the user to be verified.
        $user->update([
            'email_verified_at' => now()->subDay(),
        ]);

        $data = $this->requestData($user);

        $response = $this->makeRequest($data);

        $this->assertResponse($response);

        $this->assertDatabase($user);

        // Assert the verified at has not been changed.
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email_verified_at' => $user->email_verified_at->toDateTimeString(),
        ]);
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
     * Returns data used in a request.
     */
    private function requestData(User $user): array
    {
        return [
            'id' => $user->id,
            'verification_token' => sha1($user->getEmailForVerification()),
        ];
    }

    /**
     * Makes a request.
     */
    private function makeRequest(array $data): TestResponse
    {
        return $this->json('POST', '/auth/verify', $data);
    }

    /**
     * Creates a signed url for the auth verification route.
     */
    private function verificationUrl(int $expiration = 60): string
    {
        return URL::temporarySignedRoute(
            'auth.verification',
            now()->addMinutes($expiration),
        );
    }

    /**
     * Asserts a response.
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
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'email_verified_at' => null,
        ]);
    }
}
