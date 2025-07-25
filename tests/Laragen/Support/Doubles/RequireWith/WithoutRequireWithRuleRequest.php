<?php

namespace Tests\Laragen\Support\Doubles\RequireWith;

use Illuminate\Foundation\Http\FormRequest;

final class WithoutRequireWithRuleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'age' => ['nullable', 'integer'],
        ];
    }
}
