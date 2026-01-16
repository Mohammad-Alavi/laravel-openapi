<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Validation;

/**
 * Result of meta-schema validation.
 */
final readonly class MetaSchemaValidationResult
{
    /**
     * @param MetaSchemaValidationError[] $errors
     */
    public function __construct(
        public bool $valid,
        public array $errors = [],
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
     * Get all error messages as strings.
     *
     * @return string[]
     */
    public function getErrorMessages(): array
    {
        return array_map(static fn ($e) => $e->message, $this->errors);
    }

    /**
     * Get errors grouped by path.
     *
     * @return array<string, MetaSchemaValidationError[]>
     */
    public function getErrorsByPath(): array
    {
        $grouped = [];
        foreach ($this->errors as $error) {
            $grouped[$error->path][] = $error;
        }

        return $grouped;
    }
}
