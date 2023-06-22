<?php

namespace Tests\Unit\Models\Concerns;

use App\Models\Concerns\HasFullName;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class TestModel extends Model
{
    use HasFullName;

    public string $first_name;

    public string $last_name;

    public ?string $full_name;
}

class HasFullNameTest extends TestCase
{
    /** @test */
    public function it_makes_full_name(): void
    {
        [$stub] = $this->prepare();

        $stub->first_name = 'Pieter';
        $stub->last_name = 'Griffin';

        $stub->save();

        $this->assertEquals('Pieter Griffin', $stub->full_name);
    }

    /** @test */
    public function it_makes_full_name_with_only_first_name(): void
    {
        [$stub] = $this->prepare();

        $stub->first_name = 'Pieter';
        $stub->last_name = '';

        $stub->save();

        $this->assertEquals('Pieter', $stub->full_name);
    }

    /** @test */
    public function it_return_null_if_both_empty(): void
    {
        [$stub] = $this->prepare();

        $stub->first_name = '';
        $stub->last_name = '';

        $stub->save();

        $this->assertNull($stub->full_name);
    }

    /**
     * Prepare the tests.
     */
    private function prepare(): array
    {
        $stub = new TestModel();

        return [$stub];
    }
}
