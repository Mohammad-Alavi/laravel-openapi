<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;

interface Reusable
{
    public static function reference(): Reference;

    public static function name(): string;
}
