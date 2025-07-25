<?php

namespace Tests\Laragen\Support\Doubles\RequireWith;

use Illuminate\Foundation\Http\FormRequest;

final class WithBeforeAfterRulesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'password' => 'string|min:8|confirmed|required',
            'address' => ['required', 'string', 'max:255'],
            'name' => ['required_with:email', 'string', 'max:20', 'min:3'],
            'email' => ['required_with:name', 'email'],
            'age' => ['nullable', 'integer'],
        ];
    }
}
