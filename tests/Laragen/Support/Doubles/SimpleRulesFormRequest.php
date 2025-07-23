<?php

namespace Tests\Laragen\Support\Doubles;

use Illuminate\Foundation\Http\FormRequest;

class SimpleRulesFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'foo' => ['string', 'min:3'],
            'bar' => 'integer|required',
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
