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
            "The value $this->value is not a valid URI.",
        );
    }

    public static function validate(string $value): void
    {
        new self($value);
    }
}
