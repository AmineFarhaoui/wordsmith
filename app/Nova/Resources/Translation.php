<?php

namespace App\Nova\Resources;

use App\Library\Enumerations\Role;
use App\Nova\Actions\ExportTranslations;
use App\Nova\Actions\ImportTranslations;
use App\Nova\Filters\Tags as TagsFilter;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\TagsField\Tags;

class Translation extends Resource
{
    /**
     * The model the resource corresponds to.
     */
    public static $model = \App\Models\Translation::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     */
    public static $title = 'key';

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * The relationships that should be eager loaded when performing an index query.
     */
    public static $with = [
        'media',
    ];

    /**
     * The columns that should be searched.
     */
    public static $search = [
        'id', 'key',
    ];

    /**
     * Get the displayable label of the resource.
     */
    public static function label(): string
    {
        return __('translations.title');
    }

    /**
     * Get the displayable singular label of the resource.
     */
    public static function singularLabel(): string
    {
        return __('translations.model');
    }

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make(__('translations.fields.project'), 'project', Project::class)
                ->searchable()
                ->sortable(),

            Text::make(__('translations.fields.key'), 'key')
                ->sortable()
                ->rules('required', 'min:2', 'max:255'),

            // Images::make(__('translations.fields.screenshots'), 'screenshots')
            //     ->hideFromIndex()
            //     ->conversionOnPreview('thumbnail'),

            Markdown::make(__('translations.fields.description'), 'description')
                ->rules('nullable', 'max:2000')
                ->hideFromIndex(),

            Boolean::make(__('translations.fields.is_nested'), 'is_nested')
                ->rules('boolean')
                ->hideFromIndex(),

            Tags::make(__('translations.fields.tags'), 'tags')
                ->canBeDeselected(),

            DateTime::make(__('general.fields.created_at'), 'created_at')
                ->displayUsing(fn ($value) => $value->diffForHumans())
                ->onlyOnDetail(),

            DateTime::make(__('general.fields.updated_at'), 'updated_at')
                ->displayUsing(fn ($value) => $value->diffForHumans())
                ->onlyOnDetail(),

            HasMany::make(__('translations.fields.translation_values'), 'translationValues', TranslationValue::class),
        ];
    }

    /**
     * Get the index query for the resource.
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        $query = parent::indexQuery($request, $query);

        if (! current_user()->hasRole(Role::ADMIN)) {
            $query->whereRelation('project.companies.users', 'users.id', current_user()->id);
        }

        return $query;
    }

    /**
     * Get the actions available for the resource.
     */
    public function actions(NovaRequest $request): array
    {
        return array_merge(parent::actions($request), [
            ExportTranslations::make(),
            ImportTranslations::make()->standalone(),
        ]);
    }

    /**
     * Get the filters available for the resource.
     */
    public function filters(NovaRequest $request): array
    {
        return [
            new TagsFilter(),
        ];
    }
}
