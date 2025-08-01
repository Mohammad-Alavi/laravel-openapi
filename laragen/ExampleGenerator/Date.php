<?php

namespace MohammadAlavi\Laragen\ExampleGenerator;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\DefinedFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;

final readonly class Date implements Example
{
    public function rule(): string
    {
        return 'date';
    }

    public function values(): array
    {
        return ['2023-10-01'];
    }

    public function format(): DefinedFormat|null
    {
        return StringFormat::DATE;
    }
}
