<?php

namespace Tests\Laragen\Support\Doubles\RequireWith;

use Illuminate\Foundation\Http\FormRequest;

final class WithEverythingMixedRulesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'password' => 'string|min:8|confirmed|required',
            'email' => ['email', 'required_with:name'],
            'address' => ['required', 'string', 'max:255'],
            'name' => ['max:20', 'required_with:email', 'string', 'min:3'],
            'age' => ['nullable', 'integer'],
        ];
    }
}
