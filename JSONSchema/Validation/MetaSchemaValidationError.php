<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Validation;

/**
 * Represents a meta-schema validation error.
 */
final readonly class MetaSchemaValidationError
{
    public const INVALID_TYPE = 'invalid_type';
    public const INVALID_VALUE = 'invalid_value';
    public const MISSING_REQUIRED = 'missing_required';

    public function __construct(
        public string $message,
        public string $path,
        public string $code,
    ) {
    }

    public function __toString(): string
    {
        return "[{$this->code}] {$this->path}: {$this->message}";
    }
}
