<?php

namespace Tests\Unit\Http\Resources;

use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Resources\MissingValue;
use Tests\TestCase;

class JsonResourceTest extends TestCase
{
    /** @test */
    public function it_gets_media(): void
    {
        [$user] = $this->prepare();

        $resource = resource($user);

        $this->assertJsonStructureSnapshot($resource->getMedia('profile_picture')->toJson());
    }

    /** @test */
    public function it_doesnt_get_media_when_not_loaded(): void
    {
        [$user] = $this->prepare();

        // Make sure the media relation is not loaded.
        $user->unsetRelation('media');

        $resource = resource($user);

        $this->assertInstanceOf(MissingValue::class, $resource->getMedia('profile_picture'));
    }

    /** @test */
    public function it_doesnt_get_media_when_collection_doesnt_exist(): void
    {
        [$user] = $this->prepare();

        $this->expectExceptionMessage('Collection [unknown] doesn\'t exist.');

        resource($user)->getMedia('unknown');
    }

    /** @test */
    public function it_doesnt_get_media_when_collection_isnt_singular(): void
    {
        [$user] = $this->prepare();

        $user->addMediaCollection('multiple');

        $this->expectExceptionMessage('Collection [multiple] isn\'t a single file collection.');

        resource($user)->getMedia('multiple');
    }

    /**
     * Prepare the tests.
     */
    private function prepare(): array
    {
        $user = User::factory()
            ->withMedia('profile_picture')
            ->create();

        return [$user->load('media')];
    }
}
