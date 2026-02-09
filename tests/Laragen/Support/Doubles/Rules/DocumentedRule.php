<?php

declare(strict_types=1);

namespace Tests\Laragen\Support\Doubles\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class DocumentedRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
    }

    /** @return array{type?: string, format?: string, description?: string, enum?: string[]} */
    public function docs(): array
    {
        return [
            'type' => 'string',
            'format' => 'date-time',
            'description' => 'A valid datetime string',
        ];
    }
}
