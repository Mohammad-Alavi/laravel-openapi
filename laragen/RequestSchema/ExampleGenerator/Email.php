<?php

namespace MohammadAlavi\Laragen\RequestSchema\ExampleGenerator;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\DefinedFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;

final readonly class Email extends Example
{
    public static function rule(): string
    {
        return 'email';
    }

    public function values(): array
    {
        return ['example@laragen.com'];
    }

    public function format(): DefinedFormat
    {
        return StringFormat::EMAIL;
    }
}
