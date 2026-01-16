<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Rules;

use Webmozart\Assert\Assert;

final readonly class URL
{
    /**
     * Validates that the value is a valid URL.
     *
     * Uses PHP's FILTER_VALIDATE_URL which checks for a valid URL format
     * including scheme (http, https, etc.) and host.
     */
    public function __construct(
        private string $value,
    ) {
        Assert::true(
            false !== filter_var($this->value, FILTER_VALIDATE_URL),
            sprintf('The value "%s" is not a valid URL.', $this->value),
        );
    }
}
