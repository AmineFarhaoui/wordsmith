<?php

namespace Tests\Unit\Library\Translations\Imports;

use App\Library\Translations\Imports\JsonImport;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class JsonImportTest extends TestCase
{
    /** @test */
    public function it_reads_nested_keys(): void
    {
        $data = $this->getTestData();

        $import = new JsonImport(
            UploadedFile::fake()->createWithContent('💩.json', json_encode($data)),
        );

        $this->assertJsonStructureSnapshot($import->data()->toArray());
    }

    /**
     * Get the test data.
     */
    private function getTestData(): array
    {
        return [
            'foo.bar' => 'baz',
            'nested.notNested' => 'value',
            'nested.key' => [
                'one',
                'two',
            ],
            'nested.enums.roles' => [
                'Admin',
                'Moderator',
            ],
        ];
    }
}
