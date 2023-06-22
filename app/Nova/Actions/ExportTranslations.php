<?php

namespace App\Nova\Actions;

use App\Library\Translations\ExportFactory;
use App\Models\Translation;
use App\Models\TranslationValue;
use App\Nova\Fields\LanguageSelect;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class ExportTranslations extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     */
    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        $models = $this->filterTranslations($fields, $models);

        $export = ExportFactory::create($fields->file_type, $fields->language, $models);

        $export->save();

        return Action::downloadURL(
            $export->getDownloadableUrl(),
            $export->getDownloadableFileName(),
        );
    }

    /**
     * Get the fields available on the action.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            LanguageSelect::make(__('general.fields.language'), 'language')
                ->options(function () use ($request) {
                    $usedLanguages = TranslationValue::query()
                        ->whereRelation('translation', 'project_id', $request->viaResourceId)
                        ->groupBy('language')
                        ->pluck('language')
                        ->mapWithKeys(fn ($k) => [$k => $k]);

                    return collect(__('general.languages'))->intersectByKeys($usedLanguages);
                })
                ->rules('required', Rule::in(array_keys(__('general.languages')))),

            Select::make('File Type', 'file_type')
                ->options($exportOptions = [
                    'csv' => 'CSV',
                    'excel' => 'Excel',
                    'i18next' => 'i18next',
                    'json' => 'Simple JSON',
                ])
                ->rules('required', Rule::in(array_keys($exportOptions))),
        ];
    }

    /**
     * Filter the models that should be included in the action.
     */
    protected function filterTranslations(ActionFields $fields, Collection $models): Collection
    {
        $models = new EloquentCollection($models);

        $models = $models->load(['tags', 'translationValues' => fn ($q) => $q->where('language', $fields->language)]);

        if ($fields->tags) {
            $models = $models->filter(fn (Translation $t) => array_intersect($t->tags->pluck('id')->toArray(), $fields->tags));
        }

        return $models->toBase();
    }
}
