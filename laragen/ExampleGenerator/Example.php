<?php

namespace MohammadAlavi\Laragen\ExampleGenerator;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\DefinedFormat;

interface Example
{
    public function rule(): string;

    public function values(): array;

    public function format(): DefinedFormat|null;
}
