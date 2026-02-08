<?php

namespace Tests\Laragen\Support\Doubles;

use Illuminate\Foundation\Http\FormRequest;

class SometimesFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'nickname' => ['sometimes', 'string', 'max:100'],
            'bio' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
