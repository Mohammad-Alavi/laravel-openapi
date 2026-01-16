<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "deprecated" keyword indicates the schema is deprecated.
 *
 * Signals that applications should avoid using instances described by this schema.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-9.3
 */
final readonly class Deprecated implements Keyword
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
        return 'deprecated';
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
