<?php

namespace Tests\Unit\Models\Concerns;

use App\Models\Concerns\InteractsWithMedia;
use Tests\TestCase;

class InteractsWithMediaTest extends TestCase
{
    /** @test */
    public function it_adds_default_media_conversions(): void
    {
        [$stub] = $this->prepare();

        $stub->addDefaultMediaConversions('test');

        $this->assertCount(4, $stub->mediaConversions);
    }

    /** @test */
    public function it_adds_default_and_extra_conversions(): void
    {
        [$stub] = $this->prepare();

        $stub->addDefaultMediaConversions('test', ['thumb_xl' => 512]);

        $this->assertCount(5, $stub->mediaConversions);
    }

    /**
     * Prepares the test.
     */
    public function prepare(): array
    {
        $stub = $this->getMockForTrait(InteractsWithMedia::class);

        return [$stub];
    }
}
