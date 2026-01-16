<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;

/**
 * Boolean JSON Schema.
 *
 * In JSON Schema, `true` and `false` are valid schemas:
 * - `true`: validates any value (equivalent to an empty schema {})
 * - `false`: validates nothing (no value will pass)
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-4.3.2
 */
final readonly class BooleanSchema implements JSONSchema, \JsonSerializable
{
    private function __construct(
        private bool $value,
    ) {
    }

    /**
     * Create a schema that validates any value.
     * Equivalent to an empty schema {}.
     */
    public static function true(): self
    {
        return new self(true);
    }

    /**
     * Create a schema that validates nothing.
     * No value will pass validation.
     */
    public static function false(): self
    {
        return new self(false);
    }

    /**
     * Create from a boolean value.
     */
    public static function create(bool $value): self
    {
        return new self($value);
    }

    public function value(): bool
    {
        return $this->value;
    }

    public function isTrue(): bool
    {
        return true === $this->value;
    }

    public function isFalse(): bool
    {
        return false === $this->value;
    }

    public function jsonSerialize(): bool
    {
        return $this->value;
    }
}
