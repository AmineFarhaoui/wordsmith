<?php

namespace Tests\Unit\Library\Translations\Imports;

use App\Library\Translations\Imports\I18nextImport;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class I18nextImportTest extends TestCase
{
    /** @test */
    public function it_reads_nested_keys(): void
    {
        $data = $this->getTestData();

        $import = new I18nextImport(
            UploadedFile::fake()->createWithContent('i18next.json', json_encode($data)),
        );

        $this->assertJsonStructureSnapshot($import->data()->toArray());
    }

    /**
     * Get the test data.
     */
    private function getTestData(): array
    {
        return [
            'foo' => [
                'bar' => 'baz',
            ],
            'nested' => [
                'notNested' => 'value',
                'key' => [
                    'one',
                    'two',
                ],
                'enums' => [
                    'roles' => [
                        'Admin',
                        'Moderator',
                    ],
                ],
            ],
        ];
    }
}
