<?php

namespace Tests\Unit\Models\Concerns;

use App\Models\Concerns\ScopeDistinctColumns;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class ScopeDistinctColumnsTest extends TestCase
{
    /** @test */
    public function it_filter_distinct_records_with_specific_columns(): void
    {
        $this->prepare();

        $waypoints = TestDistinctUser::distinctColumns('first_name', 'last_name')->get();

        $this->assertJsonStructureSnapshot($waypoints);

        $waypoints = TestDistinctUser::distinctColumns('last_name')->get();

        $this->assertJsonStructureSnapshot($waypoints);
    }

    /**
     * Prepare the test.
     */
    private function prepare(): void
    {
        TestDistinctUser::create([
            'first_name' => 'Dees-jan',
            'last_name' => 'Pieterson',
        ]);

        TestDistinctUser::create([
            'first_name' => 'Dees-jan',
            'last_name' => 'Pieterson',
        ]);

        TestDistinctUser::create([
            'first_name' => 'Payam-jan',
            'last_name' => 'Pieterson',
        ]);
    }
}

class TestDistinctUser extends Model
{
    use ScopeDistinctColumns;

    /**
     * The attributes that aren't mass assignable.
     */
    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;
}
