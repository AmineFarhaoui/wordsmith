<?php

namespace App\Nova\Resources;

use App\Library\Enumerations\Role;
use App\Nova\Fields\LanguageSelect;
use App\Rules\FilteredText;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Project extends Resource
{
    /**
     * The model the resource corresponds to.
     */
    public static $model = \App\Models\Project::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     */
    public static $title = 'name';

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
        'id', 'name',
    ];

    /**
     * Get the displayable label of the resource.
     */
    public static function label(): string
    {
        return __('projects.title');
    }

    /**
     * Get the displayable singular label of the resource.
     */
    public static function singularLabel(): string
    {
        return __('projects.model');
    }

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Images::make(__('general.fields.logo'), 'logo')
                ->conversionOnIndexView('thumb_sm')
                ->conversionOnDetailView('thumb_md')
                ->conversionOnForm('thumb_md')
                ->conversionOnPreview('thumb_lg')
                ->temporary(now()->addSeconds(config('filesystems.disks.s3.sign_expiration'))),

            Text::make(__('projects.fields.name'), 'name')
                ->rules('required', 'min:2', 'max:125', new FilteredText()),

            LanguageSelect::make(__('projects.fields.default_language'), 'default_language')
                ->rules('required', Rule::in(array_keys(__('general.languages')))),

            Markdown::make(__('projects.fields.description'), 'description')
                ->rules('nullable', 'max:2000')
                ->hideFromIndex(),

            DateTime::make(__('general.fields.created_at'), 'created_at')
                ->displayUsing(fn ($value) => $value->diffForHumans())
                ->onlyOnDetail(),

            DateTime::make(__('general.fields.updated_at'), 'updated_at')
                ->displayUsing(fn ($value) => $value->diffForHumans())
                ->onlyOnDetail(),

            HasMany::make(__('projects.fields.translations'), 'translations', Translation::class),
        ];
    }

    /**
     * Get the index query for the resource.
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        $query = parent::indexQuery($request, $query);

        if (! current_user()->hasRole(Role::ADMIN)) {
            $query->whereRelation('companies.users', 'users.id', current_user()->id);
        }

        return $query;
    }
}
