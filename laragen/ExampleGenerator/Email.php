<?php

namespace MohammadAlavi\Laragen\ExampleGenerator;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\DefinedFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;

final readonly class Email implements Example
{
    public function rule(): string
    {
        return 'email';
    }

    public function values(): array
    {
        return ['example@laragen.com'];
    }

    public function format(): DefinedFormat|null
    {
        return StringFormat::EMAIL;
    }
}
