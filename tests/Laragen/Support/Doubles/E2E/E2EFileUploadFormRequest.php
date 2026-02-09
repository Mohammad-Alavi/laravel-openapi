<?php

declare(strict_types=1);

namespace Tests\Laragen\Support\Doubles\E2E;

use Illuminate\Foundation\Http\FormRequest;

final class E2EFileUploadFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'avatar' => ['required', 'image', 'max:2048'],
            'caption' => ['string', 'max:255'],
        ];
    }
}
