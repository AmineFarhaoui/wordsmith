<?php

namespace App\Nova\Fields;

use Laravel\Nova\Fields\Boolean;

class DatetimeBoolean extends Boolean
{
    /**
     * Create a new datetime boolean instance.
     */
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback ?? function () {
            return data_get($this->resource, $this->attribute) !== null;
        });

        $this->fillCallback = function ($request, $model, $attribute) {
            $value = (bool) $request->input($attribute);

            // We know that the field is verified when the timestamp is not
            // null, which we can convert to a boolean. So if the value is not
            // equal to the bool value we know the value has been changed.
            if ($value !== (data_get($model, $this->attribute) !== null)) {
                $model->{$this->attribute} = $value ? now() : null;
            }
        };
    }
}
