<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Reusable;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ReusableRefObj;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Fields\Ref;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;

abstract class ReusableComponent extends Reusable implements ReusableRefObj
{
    final public static function ref(): Reference
    {
        return Reference::create(Ref::create(self::path()));
    }
}
