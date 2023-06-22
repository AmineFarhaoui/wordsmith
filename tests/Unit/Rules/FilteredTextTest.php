<?php

namespace Tests\Unit\Rules;

use App\Rules\FilteredText;
use Tests\TestCase;

class FilteredTextTest extends TestCase
{
    /** @test */
    public function it_passes(): void
    {
        $this->assert('Aa1 !$,."\'()_-', true);
        $this->assert(null, true);
    }

    /** @test */
    public function it_fails(): void
    {
        $this->assert('@', false);
        $this->assert('#', false);
        $this->assert('%', false);
        $this->assert('^', false);
        $this->assert('&', false);
        $this->assert('*', false);
        $this->assert('+', false);
        $this->assert('=', false);
        $this->assert('{', false);
        $this->assert(':', false);
        $this->assert(';', false);
        $this->assert('🍆', false);
    }

    /** @test */
    public function it_has_a_message(): void
    {
        $this->assertMatchesSnapshot((new FilteredText())->message());
    }

    /**
     * Assert the validation rule.
     */
    private function assert(?string $text, bool $assert): void
    {
        $rule = new FilteredText();

        $this->assertEquals($assert, $rule->passes('', $text));
    }
}
