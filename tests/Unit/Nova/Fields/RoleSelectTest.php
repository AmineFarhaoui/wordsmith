<?php

namespace Tests\Unit\Nova\Fields;

use App\Library\Enumerations\Role;
use App\Models\User;
use App\Nova\Fields\RoleSelect;
use Laravel\Nova\Http\Requests\NovaRequest;
use Tests\TestCase;

class RoleSelectTest extends TestCase
{
    /** @test */
    public function it_resolves_role_value(): void
    {
        [$field, $user] = $this->prepare();

        $user->assignRole(Role::ADMIN);

        $field->resolve($user);

        $this->assertSame(Role::ADMIN, $field->value);
    }

    /** @test */
    public function it_displays_role_description()
    {
        [$field, $user] = $this->prepare();

        $user->assignRole(Role::ADMIN);

        $field->resolveForDisplay($user);

        $this->assertSame(Role::getDescription(Role::ADMIN), $field->displayedAs);
    }

    /** @test */
    public function it_fills_database_with_role_value()
    {
        [$field, $user] = $this->prepare();

        $request = new NovaRequest();

        $request->query->add(['role' => Role::ADMIN]);

        $field->fill($request, $user);

        $user->save();

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => Role::ADMIN,
            'model_id' => $user->id,
            'model_type' => (new $user)->getMorphClass(),
        ]);
    }

    /** @test */
    public function it_removes_role_from_database()
    {
        [$field, $user] = $this->prepare();

        $user->assignRole(Role::ADMIN);

        $request = new NovaRequest();

        $request->query->add(['role' => null]);

        $field->fill($request, $user);

        $user->save();

        $this->assertDatabaseMissing('model_has_roles', [
            'role_id' => Role::ADMIN,
            'model_id' => $user->id,
            'model_type' => (new $user)->getMorphClass(),
        ]);
    }

    /**
     * Prepares the test.
     */
    private function prepare(): array
    {
        $field = RoleSelect::make('Role');

        $user = User::factory()->create();

        return [$field, $user];
    }
}
