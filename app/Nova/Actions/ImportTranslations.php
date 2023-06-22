<?php

namespace App\Nova\Actions;

use App\Library\Services\TranslationService;
use App\Library\Translations\ImportFactory;
use App\Models\Project;
use App\Nova\Fields\LanguageSelect;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Validation\Rule;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class ImportTranslations extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     */
    public function handle(ActionFields $fields): ActionResponse
    {
        // Find the project via the resource ID which is set in the Nova
        // request.
        $project = Project::find(request('viaResourceId'));

        if (! $project) {
            // TODO: Translations.
            return Action::danger('No project model found.');
        }

        $importer = ImportFactory::create($fields->file_type, $fields->file);

        app(TranslationService::class)->saveManyRawTranslations(
            $project,
            $importer->data(),
            $fields->language,
            $fields->overwrite_existing_values ?? false,
            $fields->verify_translations ?? false,
        );

        // TODO: Translations.
        return Action::message('Imported translations.');
    }

    /**
     * Get the fields available on the action.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            File::make(__('general.fields.file'), 'file')
                ->rules('required', 'file', 'mimes:json,xlsx,xls,csv'),

            Select::make('File Type', 'file_type')
                ->options($importOptions = [
                    'excel' => 'Excel',
                    'i18next' => 'i18next',
                    'json' => 'Simple JSON',
                ])
                ->rules('required', Rule::in(array_keys($importOptions))),

            LanguageSelect::make(__('general.fields.language'), 'language')
                ->options(__('general.languages'))
                ->rules('required', Rule::in(array_keys(__('general.languages')))),

            Boolean::make(__('translations.importing.fields.overwriteExistingValues'), 'overwrite_existing_values')
                ->default(false)
                ->rules('boolean'),

            Boolean::make(__('translations.importing.fields.verify_translations'), 'verify_translations')
                ->default(false)
                ->rules('boolean'),
        ];
    }
}
