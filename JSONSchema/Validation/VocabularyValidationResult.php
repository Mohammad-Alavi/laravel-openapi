<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Validation;

/**
 * Result of vocabulary validation.
 */
final readonly class VocabularyValidationResult
{
    /**
     * @param VocabularyValidationError[] $errors
     * @param VocabularyValidationError[] $warnings
     * @param string[] $usedVocabularies
     * @param string[] $unknownKeywords
     */
    public function __construct(
        public bool $valid,
        public array $errors = [],
        public array $warnings = [],
        public array $usedVocabularies = [],
        public array $unknownKeywords = [],
    ) {
    }

    /**
     * Check if validation passed.
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * Check if there are any errors.
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Check if there are any warnings.
     */
    public function hasWarnings(): bool
    {
        return !empty($this->warnings);
    }

    /**
     * Get all error messages as strings.
     *
     * @return string[]
     */
    public function getErrorMessages(): array
    {
        return array_map(static fn ($e) => $e->message, $this->errors);
    }

    /**
     * Get all warning messages as strings.
     *
     * @return string[]
     */
    public function getWarningMessages(): array
    {
        return array_map(static fn ($w) => $w->message, $this->warnings);
    }
}
