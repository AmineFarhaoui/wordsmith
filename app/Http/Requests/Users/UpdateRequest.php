<?php

namespace App\Http\Requests\Users;

use App\Rules\FilteredText;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Owowagency\LaravelMedia\Rules\Base64Max;
use Owowagency\LaravelMedia\Rules\IsBase64Image;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'first_name' => [
                'string',
                'max:125',
                new FilteredText(),
            ],
            'last_name' => [
                'string',
                'max:125',
                new FilteredText(),
            ],
            'email' => [
                'email:rfc,dns',
                'max:255',
                Rule::unique('users')->ignore(current_user()),
            ],
            'password' => [
                Password::defaults(),
                'confirmed',
            ],
            'current_password' => [
                'required_with:password',
                'current_password',
            ],
            'profile_picture' => [
                new IsBase64Image(),
                new Base64Max(3000),
            ],
        ];
    }
}
