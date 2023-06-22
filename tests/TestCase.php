<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use OwowAgency\Snapshots\MatchesSnapshots;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, MatchesSnapshots, RefreshDatabase;

    /**
     * The base64 image which can be used for testing.
     */
    protected string $base64Image = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake();

        Notification::fake();
    }

    /**
     * Boot the testing helper traits.
     */
    protected function setUpTraits(): array
    {
        $uses = parent::setUpTraits();

        $this->refreshDatabase();

        return $uses;
    }
}
