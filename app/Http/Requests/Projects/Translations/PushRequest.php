<?php

namespace App\Http\Requests\Projects\Translations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PushRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:json,xlsx,xls,csv',
            'file_type' => 'required|string|in:excel,i18next,json',
            'language' => [
                'required',
                Rule::in(array_keys(__('general.languages'))),
            ],
            'overwrite_existing_values' => 'required|boolean',
            'verify_translations' => 'required|boolean',
            'tags' => 'nullable|array|prohibited_if:file_type,excel',
            'tags.*' => 'string|max:50|alpha_num:ascii',
        ];
    }
}
