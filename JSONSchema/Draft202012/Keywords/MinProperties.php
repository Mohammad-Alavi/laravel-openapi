<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;
use Webmozart\Assert\Assert;

/**
 * The "minProperties" keyword sets the minimum number of properties in an object.
 *
 * The object must have at least this many properties.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-6.5.2
 */
final readonly class MinProperties implements Keyword
{
    private function __construct(
        private int $value,
    ) {
    }

    public static function create(int $value): self
    {
        Assert::greaterThanEq($value, 0);

        return new self($value);
    }

    public static function name(): string
    {
        return 'minProperties';
    }

    public function jsonSerialize(): int
    {
        return $this->value();
    }

    public function value(): int
    {
        return $this->value;
    }
}
