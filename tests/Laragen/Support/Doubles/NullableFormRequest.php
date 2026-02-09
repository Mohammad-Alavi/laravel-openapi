<?php

namespace Tests\Laragen\Support\Doubles;

use Illuminate\Foundation\Http\FormRequest;

class NullableFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
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
