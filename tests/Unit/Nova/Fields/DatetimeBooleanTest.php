<?php

namespace Tests\Unit\Nova\Fields;

use App\Models\User;
use App\Nova\Fields\DatetimeBoolean;
use DateTime;
use Laravel\Nova\Http\Requests\NovaRequest;
use Tests\TestCase;

class DatetimeBooleanTest extends TestCase
{
    /** @test */
    public function it_resolves_null_value_to_false(): void
    {
        [$field, $user] = $this->prepare();

        $field->resolve($user);

        $this->assertSame(false, $field->value);
    }

    /** @test */
    public function it_resolves_datetime_value_to_true(): void
    {
        [$field, $user] = $this->prepare(now());

        $field->resolve($user);

        $this->assertSame(true, $field->value);
    }

    /** @test */
    public function it_sets_on_database_with_current_date_on_true(): void
    {
        $this->freezeTime();

        [$field, $user] = $this->prepare();

        $request = new NovaRequest();

        $request->query->add(['email_verified_at' => true]);

        $field->fill($request, $user);

        $user->save();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email_verified_at' => now(),
        ]);
    }

    /** @test */
    public function it_sets_on_database_with_null_on_true(): void
    {
        [$field, $user] = $this->prepare(now());

        $request = new NovaRequest();

        $request->query->add(['email_verified_at' => false]);

        $field->fill($request, $user);

        $user->save();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email_verified_at' => null,
        ]);
    }

    /**
     * Prepares the test.
     */
    private function prepare(?DateTime $value = null): array
    {
        $field = DatetimeBoolean::make('Verified', 'email_verified_at');

        $user = User::factory()->create([
            'email_verified_at' => $value,
        ]);

        return [$field, $user];
    }
}
