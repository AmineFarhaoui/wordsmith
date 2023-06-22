<?php

namespace Tests\Unit\Models\Concerns;

use App\Models\Concerns\HasPerPage;
use Tests\TestCase;

class HasPerPageTest extends TestCase
{
    /** @test */
    public function it_has_per_page(): void
    {
        [$stub] = $this->prepare();

        request()->merge(['per_page' => 1]);

        $this->assertEquals(1, $stub->getPerPage());
    }

    /** @test */
    public function it_has_default_value(): void
    {
        [$stub] = $this->prepare();

        $this->assertEquals(15, $stub->getPerPage());
    }

    /**
     * Prepare the test.
     */
    private function prepare(): array
    {
        $stub = $this->getMockForTrait(HasPerPage::class);

        $stub->perPage = 15;

        return [$stub];
    }
}
