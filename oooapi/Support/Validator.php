<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\Rules\ComponentName;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Rules\URI;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Rules\URL;

final readonly class Validator
{
    public static function url(string $value): void
    {
        new URL($value);
    }

    public static function uri(string $value): void
    {
        new URI($value);
    }

    public static function componentName(string $value): void
    {
        new ComponentName($value);
    }
}
