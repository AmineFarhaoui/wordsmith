<?php

namespace App\Http\Requests\Auth;

use App\Rules\FilteredText;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Owowagency\LaravelMedia\Rules\Base64Max;
use Owowagency\LaravelMedia\Rules\IsBase64Image;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'first_name' => [
                'required',
                'string',
                'max:125',
                new FilteredText(),
            ],
            'last_name' => [
                'required',
                'string',
                'max:125',
                new FilteredText(),
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'unique:users',
            ],
            'password' => [
                Password::required(),
            ],
            'profile_picture' => [
                'string',
                new IsBase64Image(),
                new Base64Max(3000),
            ],
        ];
    }
}
