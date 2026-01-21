<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Rules;

use Webmozart\Assert\Assert;

/**
 * Validates OpenAPI component names.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#components-object
 */
final readonly class ComponentName
{
    private const PATTERN = '/^[a-zA-Z0-9.\-_]+$/';

    public function __construct(
        private string $value,
    ) {
        Assert::regex(
            $this->value,
            self::PATTERN,
            sprintf(
                'Component name "%s" is invalid. Must match pattern %s',
                $this->value,
                self::PATTERN,
            ),
        );
    }
}
