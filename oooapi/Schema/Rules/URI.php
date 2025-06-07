<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Rules;

use Webmozart\Assert\Assert;

final readonly class URI implements Rule
{
    private function __construct(
        private string $value,
    ) {
        Assert::true(
            false !== filter_var($this->value, FILTER_VALIDATE_URL),
            sprintf('The value %s is not a valid URI.', $this->value),
        );
    }

    public static function validate(string $value): void
    {
        new self($value);
    }
}
