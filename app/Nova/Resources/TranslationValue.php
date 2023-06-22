<?php

namespace App\Nova\Resources;

use App\Library\Enumerations\Role;
use App\Nova\Actions\VerifyModel;
use App\Nova\Fields\DatetimeBoolean;
use App\Nova\Fields\LanguageSelect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class TranslationValue extends Resource
{
    /**
     * The model the resource corresponds to.
     */
    public static $model = \App\Models\TranslationValue::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     */
    public static $title = 'id';

    /**
     * Indicates if the resource should be displayed in the sidebar.
     */
    public static $displayInNavigation = false;

    /**
     * The relationships that should be eager loaded when performing an index query.
     */
    public static $with = [
        'translation',
    ];

    /**
     * The columns that should be searched.
     */
    public static $search = [
        'id', 'value',
    ];

    /**
     * Get the displayable label of the resource.
     */
    public static function label(): string
    {
        return __('translation_values.title');
    }

    /**
     * Get the displayable singular label of the resource.
     */
    public static function singularLabel(): string
    {
        return __('translation_values.model');
    }

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make(__('translation_values.fields.translation'), 'translation', Translation::class)
                ->searchable()
                ->sortable(),

            LanguageSelect::make(__('translation_values.fields.language'), 'language')
                ->rules('required', Rule::in(array_keys(__('general.languages'))))
                ->sortable(),

            Text::make(__('translation_values.fields.value'), 'value')
                ->displayUsing(fn ($value) => Str::limit($value))
                ->onlyOnIndex(),

            ...$this->getValueFields(),

            DatetimeBoolean::make(__('translation_values.fields.verified'), 'verified_at')
                ->hideWhenUpdating(fn () => $this->resource->verified_at !== null)
                ->sortable(),

            Boolean::make(__('translation_values.fields.keep_verified'), 'keep_verified')
                ->onlyOnForms()
                ->hideWhenCreating()
                ->hideWhenUpdating(fn () => $this->resource->verified_at === null)
                ->fillUsing(fn () => null)
                ->rules('boolean'),

            DateTime::make(__('general.fields.created_at'), 'created_at')
                ->displayUsing(fn ($value) => $value->diffForHumans())
                ->onlyOnDetail(),

            DateTime::make(__('general.fields.updated_at'), 'updated_at')
                ->displayUsing(fn ($value) => $value->diffForHumans())
                ->onlyOnDetail(),
        ];
    }

    /**
     * Get the value fields.
     */
    protected function getValueFields(): array
    {
        $default = Textarea::make(__('translation_values.fields.value'), 'value')
            ->rules('required', 'max:5000')
            ->alwaysShow()
            ->hideFromIndex();

        $nested = Code::make(__('translation_values.fields.value'), 'value')
            ->resolveUsing(fn ($value) => json_decode($value, true))
            ->json()
            ->rules([
                'required',
                'max:5000',
                'json',
                function ($attribute, $value, $fail) {
                    $array = json_decode($value, true);

                    if (! is_array($array)) {
                        // TODO: Translate.
                        $fail($attribute.' is not a valid JSON array.');
                    } elseif (! (array_keys($array) === range(0, count($array) - 1))) {
                        // TODO: Translate.
                        $fail($attribute.' is not a sequential JSON array.');
                    }
                },
            ])
            ->hideFromIndex();

        return [
            $this->addNestedToValueField($default, false),
            $this->addNestedToValueField($nested, true),
        ];
    }

    /**
     * Add the nested condition to the value field.
     */
    protected function addNestedToValueField(Field $field, bool $mustBeNested): Field
    {
        return $field->hideFromDetail(fn () => $this->model()->translation->is_nested !== $mustBeNested)
            ->dependsOn(
                ['translation'],
                function (Field $field, NovaRequest $request, FormData $formData) use ($mustBeNested) {
                    $id = $request->isCreateOrAttachRequest()
                        ? $request->viaResourceId
                        : $formData->get('translation');

                    $translation = \App\Models\Translation::find($id);

                    if ($translation?->is_nested !== $mustBeNested) {
                        $field->hide();
                    }
                },
            );
    }

    /**
     * Get the index query for the resource.
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        $query = parent::indexQuery($request, $query);

        if (! current_user()->hasRole(Role::ADMIN)) {
            $query->whereRelation(
                'translation.project.companies.users',
                'users.id',
                current_user()->id,
            );
        }

        return $query;
    }

    /**
     * Get the actions available for the resource.
     */
    public function actions(NovaRequest $request): array
    {
        return [
            VerifyModel::make()
                ->canSeeForModel(\App\Models\TranslationValue::class),
        ];
    }
}
