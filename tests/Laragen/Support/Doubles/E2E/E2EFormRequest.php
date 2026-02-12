<?php

namespace Tests\Laragen\Support\Doubles\E2E;

use Illuminate\Foundation\Http\FormRequest;

final class E2EFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'status' => ['in:draft,published,archived'],
            'notify' => ['boolean'],
        ];
    }
}
