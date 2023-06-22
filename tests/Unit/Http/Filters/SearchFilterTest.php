<?php

namespace Tests\Unit\Http\Filters;

use App\Http\Filters\SearchFilter;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Tests\TestCase;

class SearchFilterTest extends TestCase
{
    /** @test */
    public function it_filters(): void
    {
        [$props] = $this->prepare();

        $query = $this->buildQuery($props, 'pieter');

        $this->assertJsonStructureSnapshot($query->get());

        $query = $this->buildQuery($props, 'fin');

        $this->assertJsonStructureSnapshot($query->get());

        $query = $this->buildQuery($props, 'pit');

        $this->assertJsonStructureSnapshot($query->get());
    }

    /**
     * Prepare the tests.
     */
    private function prepare(): array
    {
        // To avoid creating similar user we use first and last name
        User::factory(3)->create([
            'first_name' => 'Jessica',
            'last_name' => 'Alba',
        ]);

        User::factory()->create([
            'first_name' => 'Pieter',
            'last_name' => 'Griffin',
            'email' => 'pit69@gmail.com',
        ]);

        $props = ['first_name', 'last_name', 'email'];

        return [$props];
    }

    /*
     * Build the location filter.
     */
    private function buildQuery(array $props, string $searchValue): Builder
    {
        $filter = new SearchFilter($props);

        $filter($query = User::query(), $searchValue, '');

        return $query;
    }
}
