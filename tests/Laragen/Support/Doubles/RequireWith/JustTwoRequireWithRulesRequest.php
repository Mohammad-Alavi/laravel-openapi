<?php

namespace Tests\Laragen\Support\Doubles\RequireWith;

use Illuminate\Foundation\Http\FormRequest;

final class JustTwoRequireWithRulesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:20', 'min:3', 'required_with:email'],
            'email' => ['required_with:name', 'email'],
        ];
    }
}
