<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Validation\Contracts;

/**
 * Contract for format validators.
 *
 * Format validators can optionally be registered with a FormatRegistry
 * to provide validation logic for custom format strings.
 *
 * @see https://json-schema.org/understanding-json-schema/reference/string#format
 */
interface FormatValidator
{
    /**
     * Validate a value against this format.
     */
    public function validate(mixed $value): bool;

    /**
     * Get the error message when validation fails.
     */
    public function errorMessage(): string;
}
