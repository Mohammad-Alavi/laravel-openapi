<?php

namespace Tests\Laragen\Support\Doubles;

use Illuminate\Foundation\Http\FormRequest;

class NestedRulesFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user' => ['required', 'array'],
            'user.name' => ['required', 'string', 'max:255'],
            'user.email' => ['required', 'email'],
            'tags' => ['array'],
            'tags.*' => ['string', 'max:50'],
        ];
    }
}
