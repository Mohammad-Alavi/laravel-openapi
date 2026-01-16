<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;
use Webmozart\Assert\Assert;

/**
 * The "maxLength" keyword sets the maximum length for strings.
 *
 * The string's length (in Unicode code points) must not exceed this value.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-6.3.1
 */
final readonly class MaxLength implements Keyword
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
        return 'maxLength';
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
