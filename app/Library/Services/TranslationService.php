<?php

namespace App\Library\Services;

use App\Http\Filters\TranslationTagsFilter;
use App\Http\Filters\TranslationValueLanguageFilter;
use App\Library\Translations\RawTranslation;
use App\Models\Project;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class TranslationService
{
    /**
     * Create a length aware paginator for the given subject using the Spatie
     * Query Builder.
     */
    public function index(Relation|Builder|string $subject): QueryBuilder
    {
        return QueryBuilder::for($subject)
            ->allowedFilters([
                AllowedFilter::custom('tags.name', new TranslationTagsFilter()),
                AllowedFilter::custom('translation_values.language', new TranslationValueLanguageFilter()),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts([
                'id',
                AllowedSort::field('createdAt', 'created_at'),
                AllowedSort::field('updatedAt', 'updated_at'),
            ]);
    }

    /**
     * Save data to a translation.
     */
    public function save(Translation $translation, array $data): Translation
    {
        $translation->fill($data);

        if (array_key_exists('tags', $data)
            && $data['tags'] !== null) {
            $translation->attachTags($data['tags']);
        }

        return tap($translation)->save();
    }

    /**
     * Save many raw translations.
     */
    public function saveManyRawTranslations(
        Project $project,
        Collection $rawTranslations,
        string $language,
        bool $overwrite = false,
        bool $verify = false,
    ): void {
        $rawTranslations->each(fn ($rawTranslation) => $this->saveRawTranslation(
            $project,
            $language,
            $rawTranslation,
            $overwrite,
            $verify,
        ));
    }

    /**
     * Save a raw translation.
     */
    public function saveRawTranslation(
        Project $project,
        string $language,
        RawTranslation $rawTranslation,
        bool $overwrite = false,
        bool $verify = false,
    ): Translation {
        $translation = $project->translations()->firstOrCreate([
            'project_id' => $project->id,
            'key' => $rawTranslation->getKey(),
        ], ['description' => $rawTranslation->getDescription()]);

        // This makes sure that the translation stays nested if it already had a
        // nested value. And if the translation already didn't have a nested
        // value, it will only be nested if the raw translation is nested.
        if ($rawTranslation->getIsNested() || $translation->is_nested) {
            $translation->is_nested = true;
            $translation->save();
        }

        $this->save($translation, [
            'tags' => $rawTranslation->getTags(),
        ]);

        if ($overwrite) {
            $translation = $this->save($translation, [
                'description' => $rawTranslation->getDescription(),
                'is_nested' => $translation->is_nested ?? $rawTranslation->getIsNested(),
            ]);
        }

        if ($rawTranslation->getValue()) {
            $translation->setTranslation(
                $language,
                $rawTranslation->getValue(),
                $overwrite,
                $verify,
            );
        }

        return $translation;
    }
}
