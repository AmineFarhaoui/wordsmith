<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * List all models which should be added to the morph map here.
     */
    protected array $modelsForMorphMap = [
        \App\Models\Company::class,
        \App\Models\Project::class,
        \App\Models\Translation::class,
        \App\Models\TranslationValue::class,
        \App\Models\User::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->createMorphMap();
    }

    /**
     * Create the morph map from the given models.
     */
    private function createMorphMap(): void
    {
        $morphMap = [];

        foreach ($this->modelsForMorphMap as $key => $model) {
            $key = is_string($key) ? $key : (new $model)->getTable();

            $morphMap[$key] = $model;
        }

        Relation::enforceMorphMap($morphMap);
    }
}
