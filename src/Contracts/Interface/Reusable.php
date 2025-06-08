<?php

namespace MohammadAlavi\LaravelOpenApi\Contracts\Interface;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\SimpleCreator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;

interface Reusable extends SimpleCreator
{
    public static function ref(): Reference|string;

    public static function key(): string;
}
