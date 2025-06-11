<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;

interface Reusable
{
    public static function ref(): Reference|string;

    public static function key(): string;
}
