<?php

namespace MohammadAlavi\Laragen\RequestSchema\ExampleGenerator;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\DefinedFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;

final readonly class Date extends Example
{
    public static function rule(): string
    {
        return 'date';
    }

    public function values(): array
    {
        return ['2023-10-01'];
    }

    public function format(): DefinedFormat
    {
        return StringFormat::DATE;
    }
}
