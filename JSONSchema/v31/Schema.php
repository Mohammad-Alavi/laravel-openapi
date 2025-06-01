<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\v31;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Descriptors\ArrayDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Descriptors\BooleanDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Descriptors\ConstantDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Descriptors\EnumDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Descriptors\IntegerDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Descriptors\NullDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Descriptors\NumberDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Descriptors\ObjectDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Descriptors\StringDescriptor;

final class Schema extends Descriptor implements NullDescriptor, BooleanDescriptor, StringDescriptor, IntegerDescriptor, NumberDescriptor, ObjectDescriptor, ArrayDescriptor, ConstantDescriptor, EnumDescriptor
{
    public static function null(): NullDescriptor
    {
        return parent::create()->type(Type::null());
    }

    public static function boolean(): BooleanDescriptor
    {
        return parent::create()->type(Type::boolean());
    }

    public static function string(): StringDescriptor
    {
        return parent::create()->type(Type::string());
    }

    public static function integer(): IntegerDescriptor
    {
        return parent::create()->type(Type::integer());
    }

    public static function number(): NumberDescriptor
    {
        return parent::create()->type(Type::number());
    }

    public static function object(): ObjectDescriptor
    {
        return parent::create()->type(Type::object());
    }

    public static function array(): ArrayDescriptor
    {
        return parent::create()->type(Type::array());
    }

    public static function constant(mixed $value): ConstantDescriptor
    {
        return parent::create()->const($value);
    }

    public static function enumerator(mixed ...$value): EnumDescriptor
    {
        return parent::create()->enum(...$value);
    }
}
