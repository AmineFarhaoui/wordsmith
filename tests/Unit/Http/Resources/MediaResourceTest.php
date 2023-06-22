<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\MediaResource;
use App\Models\Media;
use App\Models\User;
use Mockery;
use Tests\TestCase;

class MediaResourceTest extends TestCase
{
    /** @test */
    public function to_array()
    {
        // Make sure the media is not an image.
        [$media] = $this->prepare();

        $resource = MediaResource::make($media)->toArray(null);

        $this->assertMatchesJsonSnapshot($resource);
    }

    /** @test */
    public function to_array_image()
    {
        // Make sure the media is an image.
        [$media] = $this->prepare('image');

        $resource = MediaResource::make($media)->toArray(null);

        $this->assertMatchesJsonSnapshot($resource);
    }

    /** @test */
    public function to_array_image_force()
    {
        // Make sure the media is an image.
        [$media] = $this->prepare('image');

        $resource = MediaResource::make($media, true)->toArray(null);

        $this->assertMatchesJsonSnapshot($resource);
    }

    /** @test */
    public function is_image()
    {
        // Make sure the media is an image.
        [$media] = $this->prepare('image');

        $resource = MediaResource::make($media);

        $this->assertTrue($resource->isImage());
    }

    /** @test */
    public function is_not_image()
    {
        // Make sure the media is not an image.
        [$media] = $this->prepare();

        $resource = MediaResource::make($media);

        $this->assertFalse($resource->isImage());
    }

    /** @test */
    public function get_url()
    {
        [$media] = $this->prepare();

        // Create mock ...
        $mock = Mockery::mock($media);

        // ... and prepare expectations.
        $mock->shouldReceive('getFullUrl')->once();

        $mock->shouldNotReceive('getTemporaryUrl');

        // Make resource with mock.
        $resource = MediaResource::make($mock);

        $resource->getUrl();
    }

    /** @test */
    public function get_url_s3()
    {
        // Make sure disk is set to s3
        [$media] = $this->prepare('file', 's3');

        // Create mock ...
        $mock = Mockery::mock($media);

        // ... and prepare expectations.
        $mock->shouldReceive('getTemporaryUrl')->once();

        $mock->shouldNotReceive('getFullUrl');

        // Make resource with mock.
        $resource = MediaResource::make($mock);

        $resource->getUrl();
    }

    /**
     * Prepares for tests.
     */
    private function prepare(string $mime = 'file', string $disk = 'public'): array
    {
        $model = User::factory()->create();

        $media = Media::factory()
            ->forModel($model)
            ->create([
                'mime_type' => $mime,
                'disk' => $disk,
                'file_name' => 'some_file_name',
                'size' => 0,
            ]);

        return [$media];
    }
}
