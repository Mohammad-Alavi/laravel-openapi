<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Validation;

/**
 * Represents a vocabulary validation error.
 */
final readonly class VocabularyValidationError
{
    public const UNKNOWN_KEYWORD = 'unknown_keyword';
    public const MISSING_REQUIRED_VOCABULARY = 'missing_required_vocabulary';
    public const UNSUPPORTED_KEYWORD = 'unsupported_keyword';

    public function __construct(
        public string $message,
        public string $subject,
        public string $code,
    ) {
    }

    public function __toString(): string
    {
        return "[{$this->code}] {$this->message}";
    }
}
