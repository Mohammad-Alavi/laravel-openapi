<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Rules\URI;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Rules\URL;

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
}
