<?php

namespace MohammadAlavi\LaravelOpenApi\Factories;

use MohammadAlavi\ObjectOrientedOAS\Objects\Schema;

abstract class ExtensionFactory
{
    abstract public function key(): string;

    abstract public function value(): array|string|Schema|null;
}
