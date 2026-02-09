<?php

declare(strict_types=1);

namespace Tests\Laragen\Support\Doubles\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class EnumDocumentedRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
    }

    /** @return array{type?: string, enum?: string[]} */
    public function docs(): array
    {
        return [
            'type' => 'string',
            'enum' => ['active', 'inactive', 'pending'],
        ];
    }
}
