<?php

namespace Tests\Feature\Nova;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Tests\Support\Nova\NovaTests;
use Tests\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    use NovaTests;

    /**
     * The admin user which is used for authentication.
     */
    protected User $admin;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();

        $this->actingAs($this->admin, 'web');
    }

    /**
     * Assert nova create request.
     */
    protected function assertCreateRequest(bool $pass, string $model, array $data, array $errors = []): void
    {
        $response = $this->novaPost($model, $data);

        if ($pass) {
            $response->assertSuccessful()->assertSessionHasNoErrors();
        } elseif ($errors) {
            $response->assertSessionHasErrors($errors);
        } else {
            $response->assertSessionHas('errors');
        }
    }

    /**
     * Assert nova update request.
     */
    protected function assertUpdateRequest(bool $pass, Model $model, array $data, array $errors = []): void
    {
        $response = $this->novaPut($model, $data);

        if ($pass) {
            $response->assertSuccessful()->assertSessionHasNoErrors();
        } elseif ($errors) {
            $response->assertSessionHasErrors($errors);
        } else {
            $response->assertSessionHas('errors');
        }
    }
}
