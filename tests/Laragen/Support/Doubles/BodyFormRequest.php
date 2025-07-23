<?php

namespace Tests\Laragen\Support\Doubles;

use Illuminate\Foundation\Http\FormRequest;

class BodyFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'foo' => ['string', 'min:3'],
            'bar' => ['integer'],
            'name' => ['required_without:email', 'string', 'max:255'],
            'email' => 'required_without:name|email|max:255',
            'password' => 'string|min:8|confirmed',
            'age' => ['nullable', 'integer', 'between:18,99'],
        ];
    }

    public function messages(): array
    {
        return [];
    }

    public function attributes(): array
    {
        return [];
    }
}
