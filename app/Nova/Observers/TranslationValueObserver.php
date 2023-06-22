<?php

namespace App\Nova\Observers;

use App\Models\TranslationValue;

class TranslationValueObserver
{
    /**
     * Handle the TranslationValue "saving" event.
     */
    public function saving(TranslationValue $translationValue): void
    {
        // If the translation value is verified we need to determine whether the
        // user wants to keep the verification or not.
        if ($translationValue->verified_at !== null
            && request()->request->has('keep_verified')
            && ! request()->request->getBoolean('keep_verified')) {
            $translationValue->verified_at = null;
        }
    }
}
