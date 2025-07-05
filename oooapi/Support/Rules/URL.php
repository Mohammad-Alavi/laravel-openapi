<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Rules;

use Webmozart\Assert\Assert;

final readonly class URL
{
    public function __construct(
        private string $value,
    ) {
        Assert::true(
            // TODO: Consider using a more robust URL validation method.
            false !== filter_var($this->value, FILTER_VALIDATE_URL),
            sprintf('The value %s is not a valid URL.', $this->value),
        );
    }
}
