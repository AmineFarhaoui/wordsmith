<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Spatie\QueryBuilder\Filters\Filter;

class TranslationTagsFilter implements Filter
{
    /**
     * Filter models on any tags.
     */
    public function __invoke(Builder $query, $value, string $property)
    {
        return $query->withAnyTags(Arr::wrap($value));
    }
}
