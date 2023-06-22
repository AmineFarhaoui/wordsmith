<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\MediaImageResource;
use App\Models\Media;
use App\Models\User;
use Tests\TestCase;

class ImageResourceTest extends TestCase
{
    /** @test */
    public function to_array()
    {
        [$media] = $this->prepare();

        $resource = MediaImageResource::make($media)->toArray(null);

        $this->assertMatchesJsonSnapshot($resource);
    }

    /**
     * Prepares for tests.
     */
    private function prepare(): array
    {
        $model = User::factory()->create();

        $media = Media::factory()
            ->forModel($model)
            ->create(['file_name' => 'some_file_name']);

        return [$media];
    }
}
