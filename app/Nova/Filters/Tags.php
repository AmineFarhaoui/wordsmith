<?php

namespace App\Nova\Filters;

use App\Models\Translation;
use Illuminate\Support\Collection;
use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\Tags\Tag;

class Tags extends BooleanFilter
{
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        $filters = collect($value)->filter();

        if ($filters->isEmpty()) {
            return $query;
        }

        return $query->whereHas('tags', fn ($q) => $q->whereIn('tags.id', $filters->keys()));
    }

    /**
     * Get the filter's available options.
     */
    public function options(NovaRequest $request): Collection
    {
        // TODO: Need to be fixed, doesn't work yet.
        if (($model = $request->model()) instanceof Translation
            && $model->exists) {
            $model->load('project.translations.tags');

            return $model->project->translations
                ->pluck('tags')
                ->flatten()
                ->unique('id')
                ->mapWithKeys(fn ($t) => [$t->name => $t->id]);
        }

        return Tag::all()->mapWithKeys(fn ($t) => [$t->name => $t->id]);
    }
}
