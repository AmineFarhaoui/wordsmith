<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Spatie\QueryBuilder\Filters\Filter;

class TranslationValueLanguageFilter implements Filter
{
    /**
     * Filter models on language.
     */
    public function __invoke(Builder $query, $value, string $property)
    {
        return $query->whereHas('translationValues', fn ($q) => $q->whereIn('language', Arr::wrap($value)));
    }
}
