<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "readOnly" keyword indicates the value should not be modified.
 *
 * Typically used for values managed by the server (e.g., computed or auto-generated).
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-9.4
 */
final readonly class IsReadOnly implements Keyword
{
    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }

    public static function name(): string
    {
        return 'readOnly';
    }

    public function jsonSerialize(): true
    {
        return $this->value();
    }

    public function value(): true
    {
        return true;
    }
}
