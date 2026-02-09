<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RequestSchema\ValidationRules;

final readonly class DetectedValidationRules
{
    /**
     * @param array<string, mixed> $rules
     * @param class-string|null $formRequestClass
     */
    public function __construct(
        public array $rules,
        public string|null $formRequestClass = null,
    ) {
    }
}
