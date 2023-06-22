<?php

namespace App\Nova\Resources;

use App\Library\Enumerations\Role;
use App\Nova\Fields\RoleSelect;
use App\Rules\FilteredText;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Validation\Rules\Password as PasswordValidation;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     */
    public static $title = 'full_name';

    /**
     * The relationships that should be eager loaded when performing an index query.
     */
    public static $with = [
        'media', 'roles',
    ];

    /**
     * The columns that should be searched.
     */
    public static $search = [
        'id', 'full_name', 'email',
    ];

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Images::make('Profile picture')
                ->conversionOnIndexView('thumb_sm')
                ->conversionOnDetailView('thumb_md')
                ->conversionOnForm('thumb_md')
                ->conversionOnPreview('thumb_lg')
                ->temporary(now()->addSeconds(config('filesystems.disks.s3.sign_expiration'))),

            Text::make('First name')
                ->rules('required', 'min:2', 'max:125', new FilteredText())
                ->hideFromIndex(),

            Text::make('Last name')
                ->rules('required', 'min:2', 'max:125', new FilteredText())
                ->hideFromIndex(),

            Text::make('Full name')
                ->onlyOnIndex()
                ->sortable(),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email:rfc,dns', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules(PasswordValidation::required())
                ->updateRules('nullable', PasswordValidation::defaults()),

            RoleSelect::make('Role')
                ->nullable(),

            BelongsToMany::make(__('companies.title'), 'companies', Company::class),
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
