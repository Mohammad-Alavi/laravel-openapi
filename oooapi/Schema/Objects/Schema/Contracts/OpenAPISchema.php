<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\Descriptors\ArrayDescriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\Descriptors\BooleanDescriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\Descriptors\ConstantDescriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\Descriptors\EnumDescriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\Descriptors\IntegerDescriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\Descriptors\NullDescriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\Descriptors\NumberDescriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\Descriptors\ObjectDescriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\Descriptors\StringDescriptor;

final class OpenAPISchema extends Descriptor implements NullDescriptor, BooleanDescriptor, StringDescriptor, IntegerDescriptor, NumberDescriptor, ObjectDescriptor, ArrayDescriptor, ConstantDescriptor, EnumDescriptor
{
    public static function null(): NullDescriptor
    {
        return parent::withoutSchema()->type(Type::null());
    }

    public static function boolean(): BooleanDescriptor
    {
        return parent::withoutSchema()->type(Type::boolean());
    }

    public static function string(): StringDescriptor
    {
        return parent::withoutSchema()->type(Type::string());
    }

    public static function integer(): IntegerDescriptor
    {
        return parent::withoutSchema()->type(Type::integer());
    }

    public static function number(): NumberDescriptor
    {
        return parent::withoutSchema()->type(Type::number());
    }

    public static function object(): ObjectDescriptor
    {
        return parent::withoutSchema()->type(Type::object());
    }

    public static function array(): ArrayDescriptor
    {
        return parent::withoutSchema()->type(Type::array());
    }

    public static function constant(mixed $value): ConstantDescriptor
    {
        return parent::withoutSchema()->const($value);
    }

    public static function enumerator(mixed ...$value): EnumDescriptor
    {
        return parent::withoutSchema()->enum(...$value);
    }
}
