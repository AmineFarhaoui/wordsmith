<?php

namespace Tests\Unit\Library\Services;

use App\Library\Services\TranslationService;
use App\Library\Translations\RawTranslation;
use App\Models\Project;
use App\Models\Translation;
use App\Models\TranslationValue;
use Tests\TestCase;

class TranslationServiceTest extends TestCase
{
    /** @test */
    public function it_saves_many_translations(): void
    {
        $service = \Mockery::mock(TranslationService::class)->makePartial();

        $service->shouldReceive('saveRawTranslation')
            ->twice()
            ->with(
                \Mockery::type(Project::class),
                'en',
                \Mockery::type(RawTranslation::class),
                false,
                false,
            )
            ->andReturn(Translation::factory()->create());

        $translations = collect([RawTranslation::make(), RawTranslation::make()]);

        $service->saveManyRawTranslations(
            Project::factory()->create(),
            $translations,
            'en',
            false,
            false,
        );
    }

    /** @test */
    public function it_save_raw_translations(): void
    {
        $project = Project::factory()->create();

        $rawTranslation = RawTranslation::make()
            ->setKey('test.key')
            ->setValue('Test value')
            ->setDescription('Test description')
            ->setIsNested(true)
            ->setTags(['test']);

        $translation = $this->service()
            ->saveRawTranslation($project, 'en', $rawTranslation);

        $this->assertMatchesJsonSnapshot([
            'key' => $translation->key,
            'value' => $translation->translationValues->first()->value,
            'description' => $translation->description,
            'tags' => $translation->tags->map(fn ($tag) => $tag->name),
            'is_nested' => $translation->is_nested,
        ]);
    }

    /** @test */
    public function it_overwrites_raw_translations(): void
    {
        $project = Project::factory()->create();

        $translationValue = TranslationValue::factory()->create([
            'translation_id' => Translation::factory([
                'project_id' => $project->id,
                'key' => 'test.key',
            ]),
            'value' => $value = 'Old value',
            'language' => 'en',
        ]);

        $rawTranslation = RawTranslation::make()
            ->setKey($translationValue->translation->key)
            ->setValue('Test value')
            ->setDescription('Test description')
            ->setIsNested(true)
            ->setTags(['test']);

        $translation = $this->service()
            ->saveRawTranslation($project, 'en', $rawTranslation, true);

        $this->assertMatchesJsonSnapshot([
            'id' => $translationValue->translation_id,
            'key' => $translation->key,
            'value' => $value,
            'description' => $translation->description,
            'tags' => $translation->tags->map(fn ($tag) => $tag->name),
            'is_nested' => $translation->is_nested,
        ]);
    }

    /** @test */
    public function it_doesnt_overwrite_raw_translations(): void
    {
        $project = Project::factory()->create();

        $translationValue = TranslationValue::factory()->create([
            'translation_id' => Translation::factory([
                'project_id' => $project->id,
                'key' => 'test.key',
                'description' => 'Old description',
            ]),
            'value' => 'Old value',
            'language' => 'en',
        ]);

        $rawTranslation = RawTranslation::make()
            ->setKey($translationValue->translation->key)
            ->setValue('Test value')
            ->setDescription('Test description')
            ->setIsNested(true)
            ->setTags(['test']);

        $translation = $this->service()
            ->saveRawTranslation($project, 'en', $rawTranslation, false);

        $this->assertMatchesJsonSnapshot([
            'id' => $translationValue->translation_id,
            'key' => $translation->key,
            'value' => $translation->translationValues->first()->value,
            'description' => $translation->description,
            'tags' => $translation->tags->map(fn ($tag) => $tag->name),
            'is_nested' => $translation->is_nested,
        ]);
    }

    /** @test */
    public function it_verifies_raw_translations(): void
    {
        $project = Project::factory()->create();

        $translationValue = TranslationValue::factory()->create([
            'translation_id' => Translation::factory([
                'project_id' => $project->id,
                'key' => 'test.key',
                'description' => 'Old description',
            ]),
            'value' => 'Old value',
            'language' => 'en',
        ]);

        $rawTranslation = RawTranslation::make()
            ->setKey($translationValue->translation->key)
            ->setValue('Test value')
            ->setDescription('Test description')
            ->setIsNested(true)
            ->setTags(['test']);

        $translation = $this->service()
            ->saveRawTranslation($project, 'en', $rawTranslation, true, true);

        $this->assertMatchesJsonSnapshot([
            'id' => $translationValue->translation_id,
            'key' => $translation->key,
            'value' => $translation->translationValues->first()->value,
            'description' => $translation->description,
            'tags' => $translation->tags->map(fn ($tag) => $tag->name),
            'is_verified' => $translation->translationValues->first()->verified_at !== null,
        ]);
    }

    /** @test */
    public function it_saves_raw_translations_for_different_language(): void
    {
        $project = Project::factory()->create();

        $translationValue = TranslationValue::factory()->create([
            'translation_id' => Translation::factory([
                'project_id' => $project->id,
                'key' => 'test.key',
            ])->afterCreating(fn (Translation $t) => $t->attachTag('foo')),
            'language' => 'en',
            'value' => 'English value',
        ]);

        $rawTranslation = RawTranslation::make()
            ->setKey($translationValue->translation->key)
            ->setValue('Dutch value')
            ->setDescription('Test description')
            ->setIsNested(true)
            ->setTags(['test']);

        $translation = $this->service()
            ->saveRawTranslation($project, 'nl', $rawTranslation, true);

        $this->assertMatchesJsonSnapshot([
            'id' => $translationValue->translation_id,
            'key' => $translation->key,
            'values' => $translation->translationValues->map(fn ($value) => [
                'language' => $value->language,
                'value' => $value->value,
            ]),
            'description' => $translation->description,
            'tags' => $translation->tags->map(fn ($tag) => $tag->name),
            'is_nested' => $translation->is_nested,
        ]);
    }

    /**
     * Return a mocked instance of the service.
     */
    private function service(): TranslationService
    {
        return app(TranslationService::class);
    }
}
