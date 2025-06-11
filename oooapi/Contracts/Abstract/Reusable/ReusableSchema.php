<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Reusable;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ReusableSchema as ReusableSchemaContract;

abstract class ReusableSchema extends Reusable implements ReusableSchemaContract
{
    final public static function ref(): string
    {
        return self::path();
    }
}
