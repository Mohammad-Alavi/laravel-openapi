<?php

namespace Tests\Laragen\Support\Doubles\RequireWith;

use Illuminate\Foundation\Http\FormRequest;

final class AnotherWithMixedOrderRulesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'password' => 'string|min:8|confirmed|required',
            'name' => ['required_with:email', 'string', 'max:20', 'min:3'],
            'age' => ['nullable', 'integer'],
            'email' => ['required_with:name', 'email'],
            'address' => ['required', 'string', 'max:255'],
        ];
    }
}
