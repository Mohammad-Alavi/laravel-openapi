<?php

namespace MohammadAlavi\LaravelOpenApi\Contracts\Interface;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;

interface ReusableRefObj extends Reusable
{
    public static function ref(): Reference;
}
