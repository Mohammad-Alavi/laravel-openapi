<?php

namespace MohammadAlavi\Laragen\RequestSchema\ExampleGenerator;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\DefinedFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;

final readonly class Password extends Example
{
    public static function rule(): string
    {
        return \Illuminate\Validation\Rules\Password::class;
    }

    public function values(): array
    {
        return [fake()->password($this->schema->getSchemaDTO()->minLength)];
    }

    public function format(): DefinedFormat
    {
        return StringFormat::PASSWORD;
    }
}
