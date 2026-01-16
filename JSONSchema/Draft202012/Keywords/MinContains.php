<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;
use Webmozart\Assert\Assert;

/**
 * The "minContains" keyword requires a minimum number of items to match "contains".
 *
 * Used with "contains" to set a lower bound on matching items.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-6.4.5
 */
final readonly class MinContains implements Keyword
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
        return 'minContains';
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
