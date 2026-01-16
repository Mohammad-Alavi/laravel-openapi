<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Validation;

/**
 * Validates that a schema conforms to the JSON Schema Draft 2020-12 meta-schema.
 *
 * This validates the STRUCTURE and VALUES of a schema, not its vocabulary membership.
 * - VocabularyValidator: "Are keyword names valid?"
 * - MetaSchemaValidator: "Are keyword values valid?"
 *
 * Examples of what this catches:
 * - type: "stringg" (invalid type value)
 * - maxLength: "five" (should be integer)
 * - maxLength: -1 (should be non-negative)
 * - minimum: "10" (should be number)
 * - properties: ["a", "b"] (should be object)
 *
 * @see https://json-schema.org/draft/2020-12/schema
 */
final class MetaSchemaValidator
{
    private const VALID_TYPES = ['string', 'number', 'integer', 'boolean', 'null', 'array', 'object'];

    private const VALID_CONTENT_ENCODINGS = [
        '7bit', '8bit', 'binary', 'quoted-printable', 'base16', 'base32', 'base64',
    ];

    /**
     * Validate a compiled schema array against the meta-schema.
     *
     * @param array<string, mixed> $schema The compiled schema
     */
    public function validate(array $schema): MetaSchemaValidationResult
    {
        $errors = [];

        $this->validateSchema($schema, '', $errors);

        return new MetaSchemaValidationResult(
            empty($errors),
            $errors,
        );
    }

    /**
     * @param array<string, mixed> $schema
     * @param string $path JSON pointer path for error reporting
     * @param MetaSchemaValidationError[] $errors
     */
    private function validateSchema(array $schema, string $path, array &$errors): void
    {
        foreach ($schema as $keyword => $value) {
            $keywordPath = '' === $path ? $keyword : "{$path}/{$keyword}";

            match ($keyword) {
                // Core vocabulary
                '$id', '$schema', '$ref', '$anchor', '$dynamicRef', '$dynamicAnchor', '$comment' => $this->validateString($value, $keywordPath, $errors),
                '$vocabulary' => $this->validateVocabulary($value, $keywordPath, $errors),
                '$defs' => $this->validateDefs($value, $keywordPath, $errors),

                // Validation vocabulary - strings
                'title', 'description', 'pattern' => $this->validateString($value, $keywordPath, $errors),

                // Validation vocabulary - non-negative integers
                'maxLength', 'minLength', 'maxItems', 'minItems', 'maxContains', 'minContains',
                'maxProperties', 'minProperties' => $this->validateNonNegativeInteger($value, $keywordPath, $errors),

                // Validation vocabulary - numbers
                'maximum', 'minimum', 'exclusiveMaximum', 'exclusiveMinimum', 'multipleOf' => $this->validateNumber($value, $keywordPath, $errors),

                // Validation vocabulary - positive number
                // multipleOf must be > 0, already handled as number, add extra check
                // Actually multipleOf is already in numbers above, let's check positivity separately

                // Validation vocabulary - booleans
                'uniqueItems', 'deprecated', 'readOnly', 'writeOnly' => $this->validateBoolean($value, $keywordPath, $errors),

                // Validation vocabulary - type
                'type' => $this->validateType($value, $keywordPath, $errors),

                // Validation vocabulary - arrays
                'required' => $this->validateStringArray($value, $keywordPath, $errors),
                'enum' => $this->validateArray($value, $keywordPath, $errors),
                'examples' => $this->validateArray($value, $keywordPath, $errors),

                // Applicator vocabulary - schema
                'items', 'additionalProperties', 'contains', 'propertyNames',
                'if', 'then', 'else', 'not', 'unevaluatedItems', 'unevaluatedProperties',
                'contentSchema' => $this->validateSchemaOrBoolean($value, $keywordPath, $errors),

                // Applicator vocabulary - array of schemas
                'prefixItems', 'allOf', 'anyOf', 'oneOf' => $this->validateSchemaArray($value, $keywordPath, $errors),

                // Applicator vocabulary - object of schemas
                'properties', 'patternProperties', 'dependentSchemas' => $this->validateSchemaObject($value, $keywordPath, $errors),

                // Validation vocabulary - dependentRequired
                'dependentRequired' => $this->validateDependentRequired($value, $keywordPath, $errors),

                // Meta-data vocabulary
                'default' => null, // Any value is valid

                // Format vocabulary
                'format' => $this->validateString($value, $keywordPath, $errors),

                // Content vocabulary
                'contentEncoding' => $this->validateContentEncoding($value, $keywordPath, $errors),
                'contentMediaType' => $this->validateString($value, $keywordPath, $errors),

                // const - any value
                'const' => null,

                // Unknown keywords - ignore (handled by VocabularyValidator)
                default => null,
            };

            // Special case: multipleOf must be positive
            if ('multipleOf' === $keyword && is_numeric($value) && $value <= 0) {
                $errors[] = new MetaSchemaValidationError(
                    "'{$keywordPath}' must be greater than 0",
                    $keywordPath,
                    MetaSchemaValidationError::INVALID_VALUE,
                );
            }
        }
    }

    private function validateString(mixed $value, string $path, array &$errors): void
    {
        if (!is_string($value)) {
            $errors[] = new MetaSchemaValidationError(
                "'{$path}' must be a string, got " . gettype($value),
                $path,
                MetaSchemaValidationError::INVALID_TYPE,
            );
        }
    }

    private function validateNumber(mixed $value, string $path, array &$errors): void
    {
        if (!is_int($value) && !is_float($value)) {
            $errors[] = new MetaSchemaValidationError(
                "'{$path}' must be a number, got " . gettype($value),
                $path,
                MetaSchemaValidationError::INVALID_TYPE,
            );
        }
    }

    private function validateNonNegativeInteger(mixed $value, string $path, array &$errors): void
    {
        if (!is_int($value)) {
            $errors[] = new MetaSchemaValidationError(
                "'{$path}' must be an integer, got " . gettype($value),
                $path,
                MetaSchemaValidationError::INVALID_TYPE,
            );

            return;
        }

        if ($value < 0) {
            $errors[] = new MetaSchemaValidationError(
                "'{$path}' must be a non-negative integer, got {$value}",
                $path,
                MetaSchemaValidationError::INVALID_VALUE,
            );
        }
    }

    private function validateBoolean(mixed $value, string $path, array &$errors): void
    {
        if (!is_bool($value)) {
            $errors[] = new MetaSchemaValidationError(
                "'{$path}' must be a boolean, got " . gettype($value),
                $path,
                MetaSchemaValidationError::INVALID_TYPE,
            );
        }
    }

    private function validateType(mixed $value, string $path, array &$errors): void
    {
        if (is_string($value)) {
            if (!in_array($value, self::VALID_TYPES, true)) {
                $errors[] = new MetaSchemaValidationError(
                    "'{$path}' must be a valid type, got '{$value}'. Valid types: " . implode(', ', self::VALID_TYPES),
                    $path,
                    MetaSchemaValidationError::INVALID_VALUE,
                );
            }

            return;
        }

        if (is_array($value)) {
            foreach ($value as $index => $type) {
                if (!is_string($type)) {
                    $errors[] = new MetaSchemaValidationError(
                        "'{$path}[{$index}]' must be a string type",
                        "{$path}[{$index}]",
                        MetaSchemaValidationError::INVALID_TYPE,
                    );
                    continue;
                }

                if (!in_array($type, self::VALID_TYPES, true)) {
                    $errors[] = new MetaSchemaValidationError(
                        "'{$path}[{$index}]' is not a valid type: '{$type}'",
                        "{$path}[{$index}]",
                        MetaSchemaValidationError::INVALID_VALUE,
                    );
                }
            }

            return;
        }

        $errors[] = new MetaSchemaValidationError(
            "'{$path}' must be a string or array of strings",
            $path,
            MetaSchemaValidationError::INVALID_TYPE,
        );
    }

    private function validateArray(mixed $value, string $path, array &$errors): void
    {
        if (!is_array($value)) {
            $errors[] = new MetaSchemaValidationError(
                "'{$path}' must be an array, got " . gettype($value),
                $path,
                MetaSchemaValidationError::INVALID_TYPE,
            );
        }
    }

    private function validateStringArray(mixed $value, string $path, array &$errors): void
    {
        if (!is_array($value)) {
            $errors[] = new MetaSchemaValidationError(
                "'{$path}' must be an array of strings, got " . gettype($value),
                $path,
                MetaSchemaValidationError::INVALID_TYPE,
            );

            return;
        }

        foreach ($value as $index => $item) {
            if (!is_string($item)) {
                $errors[] = new MetaSchemaValidationError(
                    "'{$path}[{$index}]' must be a string, got " . gettype($item),
                    "{$path}[{$index}]",
                    MetaSchemaValidationError::INVALID_TYPE,
                );
            }
        }
    }

    private function validateSchemaOrBoolean(mixed $value, string $path, array &$errors): void
    {
        if (is_bool($value)) {
            return;
        }

        if (!is_array($value)) {
            $errors[] = new MetaSchemaValidationError(
                "'{$path}' must be a schema (object) or boolean, got " . gettype($value),
                $path,
                MetaSchemaValidationError::INVALID_TYPE,
            );

            return;
        }

        $this->validateSchema($value, $path, $errors);
    }

    private function validateSchemaArray(mixed $value, string $path, array &$errors): void
    {
        if (!is_array($value)) {
            $errors[] = new MetaSchemaValidationError(
                "'{$path}' must be an array of schemas, got " . gettype($value),
                $path,
                MetaSchemaValidationError::INVALID_TYPE,
            );

            return;
        }

        if (empty($value)) {
            $errors[] = new MetaSchemaValidationError(
                "'{$path}' must be a non-empty array",
                $path,
                MetaSchemaValidationError::INVALID_VALUE,
            );

            return;
        }

        foreach ($value as $index => $item) {
            if (!is_array($item) && !is_bool($item)) {
                $errors[] = new MetaSchemaValidationError(
                    "'{$path}[{$index}]' must be a schema (object) or boolean",
                    "{$path}[{$index}]",
                    MetaSchemaValidationError::INVALID_TYPE,
                );
                continue;
            }

            if (is_array($item)) {
                $this->validateSchema($item, "{$path}[{$index}]", $errors);
            }
        }
    }

    private function validateSchemaObject(mixed $value, string $path, array &$errors): void
    {
        if (!is_array($value)) {
            $errors[] = new MetaSchemaValidationError(
                "'{$path}' must be an object of schemas, got " . gettype($value),
                $path,
                MetaSchemaValidationError::INVALID_TYPE,
            );

            return;
        }

        foreach ($value as $key => $item) {
            if (!is_string($key)) {
                $errors[] = new MetaSchemaValidationError(
                    "'{$path}' keys must be strings",
                    $path,
                    MetaSchemaValidationError::INVALID_TYPE,
                );
                continue;
            }

            if (!is_array($item) && !is_bool($item)) {
                $errors[] = new MetaSchemaValidationError(
                    "'{$path}/{$key}' must be a schema (object) or boolean",
                    "{$path}/{$key}",
                    MetaSchemaValidationError::INVALID_TYPE,
                );
                continue;
            }

            if (is_array($item)) {
                $this->validateSchema($item, "{$path}/{$key}", $errors);
            }
        }
    }

    private function validateDefs(mixed $value, string $path, array &$errors): void
    {
        $this->validateSchemaObject($value, $path, $errors);
    }

    private function validateVocabulary(mixed $value, string $path, array &$errors): void
    {
        if (!is_array($value)) {
            $errors[] = new MetaSchemaValidationError(
                "'{$path}' must be an object, got " . gettype($value),
                $path,
                MetaSchemaValidationError::INVALID_TYPE,
            );

            return;
        }

        foreach ($value as $uri => $required) {
            if (!is_string($uri)) {
                $errors[] = new MetaSchemaValidationError(
                    "'{$path}' keys must be URI strings",
                    $path,
                    MetaSchemaValidationError::INVALID_TYPE,
                );
            }

            if (!is_bool($required)) {
                $errors[] = new MetaSchemaValidationError(
                    "'{$path}/{$uri}' must be a boolean",
                    "{$path}/{$uri}",
                    MetaSchemaValidationError::INVALID_TYPE,
                );
            }
        }
    }

    private function validateDependentRequired(mixed $value, string $path, array &$errors): void
    {
        if (!is_array($value)) {
            $errors[] = new MetaSchemaValidationError(
                "'{$path}' must be an object, got " . gettype($value),
                $path,
                MetaSchemaValidationError::INVALID_TYPE,
            );

            return;
        }

        foreach ($value as $property => $requiredProps) {
            if (!is_string($property)) {
                $errors[] = new MetaSchemaValidationError(
                    "'{$path}' keys must be property name strings",
                    $path,
                    MetaSchemaValidationError::INVALID_TYPE,
                );
                continue;
            }

            $this->validateStringArray($requiredProps, "{$path}/{$property}", $errors);
        }
    }

    private function validateContentEncoding(mixed $value, string $path, array &$errors): void
    {
        if (!is_string($value)) {
            $errors[] = new MetaSchemaValidationError(
                "'{$path}' must be a string, got " . gettype($value),
                $path,
                MetaSchemaValidationError::INVALID_TYPE,
            );

            return;
        }

        // Note: Custom encodings are allowed, but we warn for non-standard ones
        // The spec allows any string, so we don't error on unknown encodings
    }
}
