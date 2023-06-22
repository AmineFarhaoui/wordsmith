<?php

namespace Tests\Unit\Library\Translations\Exports;

use App\Library\Translations\Exports\Export;
use App\Models\Project;
use App\Models\Translation;
use App\Models\TranslationValue;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Collection;
use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /** @test */
    public function it_exports(): void
    {
        $this->assertJsonStructureSnapshot($this->export($this->getTestModels())->getContents());
    }

    /** @test */
    public function it_has_a_relative_storage_path(): void
    {
        $export = $this->export($this->getTestModels());

        $this->assertMatchesRegularExpression(
            "/^exports\/translations\/[0-9a-z\-]+.{$export->getFileExtension()}$/",
            $export->getRelativeStoragePath(),
        );
    }

    /**
     * Get the export instance.
     */
    abstract protected function export(Collection $models): Export;

    /**
     * Get the test data.
     */
    protected function getTestModels(): Collection
    {
        $project = Project::factory()->create();

        $translations = Translation::factory(2)
            ->has(
                TranslationValue::factory(['language' => 'en']),
            )
            ->state(new Sequence(
                ['key' => 'foo.bar'],
                ['key' => 'amine.the.rapper'],
            ))
            ->create([
                'project_id' => $project->id,
            ]);

        $nestedTranslations = Translation::factory(2)
            ->has(
                TranslationValue::factory([
                    'language' => 'en',
                    'value' => json_encode(['Admin', 'Moderator']),
                ]),
            )
            ->state(new Sequence(
                ['key' => 'bar.baz'],
                ['key' => 'eminem.the.rapper'],
            ))
            ->create([
                'project_id' => $project->id,
                'is_nested' => true,
            ]);

        return $translations->merge($nestedTranslations)
            ->load('translationValues');
    }
}
