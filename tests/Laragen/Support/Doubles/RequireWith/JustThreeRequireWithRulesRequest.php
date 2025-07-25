<?php

namespace Tests\Laragen\Support\Doubles\RequireWith;

use Illuminate\Foundation\Http\FormRequest;

final class JustThreeRequireWithRulesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required_with:email', 'string', 'max:20', 'min:3'],
            'email' => ['required_with:name', 'email'],
            'age' => ['required_with:name,email', 'nullable', 'integer'],
        ];
    }
}
