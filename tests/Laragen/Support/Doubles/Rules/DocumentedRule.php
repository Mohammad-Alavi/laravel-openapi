<?php

namespace Tests\Laragen\Support\Doubles\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use MohammadAlavi\LaravelRulesToSchema\Contracts\HasDocs;
use MohammadAlavi\LaravelRulesToSchema\RuleDocumentation;

class DocumentedRule implements ValidationRule, HasDocs
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
    }

    public function docs(): RuleDocumentation
    {
        return new RuleDocumentation(
            type: 'string',
            format: 'date-time',
            description: 'A valid datetime string',
        );
    }
}
