<?php

namespace App\Http\Requests\Projects\Translations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PullRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'file_type' => 'required|string|in:excel,i18next,json',
            'language' => [
                'required',
                Rule::in(array_keys(__('general.languages'))),
            ],
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50|alpha_num:ascii',
        ];
    }
}
