<?php

namespace App\Nova\Actions;

use App\Models\TranslationValue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class VerifyModel extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     */
    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        TranslationValue::withoutEvents(function () use ($models) {
            return TranslationValue::query()
                ->whereIn('id', $models->pluck('id'))
                ->unverified()
                ->touch('verified_at');
        });

        return Action::message(__('translation_values.actions.verified.success'));
    }

    /**
     * Add the can see callback for the given model class.
     */
    public function canSeeForModel(string $modelClass): self
    {
        $this->canSee(function (NovaRequest $request) use ($modelClass) {
            $resource = Str::slug((new $modelClass)->getTable());

            if ($request->resource !== $resource) {
                return false;
            }

            if (! $request->resourceId) {
                return true;
            }

            $translationValue = $modelClass::find($request->resourceId);

            return $translationValue?->verified_at === null;
        });

        return $this;
    }
}
