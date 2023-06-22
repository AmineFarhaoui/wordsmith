<?php

namespace Tests\Unit\Rules;

use App\Rules\DistinctArray;
use Tests\TestCase;

class DistinctArrayTest extends TestCase
{
    /** @test */
    public function passes(): void
    {
        $this->assertTrue(
            (new DistinctArray)->passes('', [1, 2]),
        );
    }

    /** @test */
    public function fails(): void
    {
        $this->assertFalse(
            (new DistinctArray)->passes('', [1, 1]),
        );
    }
}
