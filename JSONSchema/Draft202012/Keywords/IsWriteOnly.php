<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "writeOnly" keyword indicates the value should only be written, not read.
 *
 * Typically used for sensitive values like passwords that should not be returned.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-9.4
 */
final readonly class IsWriteOnly implements Keyword
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
        return 'writeOnly';
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
