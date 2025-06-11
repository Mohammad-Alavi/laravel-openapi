<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\Descriptors\ArrayDescriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\Descriptors\BooleanDescriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\Descriptors\ConstantDescriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\Descriptors\EnumDescriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\Descriptors\IntegerDescriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\Descriptors\NullDescriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\Descriptors\NumberDescriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\Descriptors\ObjectDescriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\Descriptors\SharedDescriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\Descriptors\StringDescriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\OpenAPISchema;

final class Schema
{
    public static function null(): NullDescriptor
    {
        return OpenAPISchema::withoutSchema()->type(Type::null());
    }

    public static function boolean(): BooleanDescriptor
    {
        return OpenAPISchema::withoutSchema()->type(Type::boolean());
    }

    public static function string(): StringDescriptor
    {
        return OpenAPISchema::string();
    }

    public static function integer(): IntegerDescriptor
    {
        return OpenAPISchema::integer();
    }

    public static function number(): NumberDescriptor
    {
        return OpenAPISchema::number();
    }

    public static function object(): ObjectDescriptor
    {
        return OpenAPISchema::object();
    }

    public static function array(): ArrayDescriptor
    {
        return OpenAPISchema::array();
    }

    public static function const(mixed $value): ConstantDescriptor
    {
        return OpenAPISchema::constant($value);
    }

    public static function enum(mixed ...$value): EnumDescriptor
    {
        return OpenAPISchema::enumerator(...$value);
    }

    public static function oneOf(OpenAPISchema ...$schemas): SharedDescriptor
    {
        return OpenAPISchema::withoutSchema()->oneOf(...$schemas);
    }

    public static function anyOf(OpenAPISchema ...$schemas): SharedDescriptor
    {
        return OpenAPISchema::withoutSchema()->anyOf(...$schemas);
    }

    public static function allOf(OpenAPISchema ...$schemas): SharedDescriptor
    {
        return OpenAPISchema::withoutSchema()->allOf(...$schemas);
    }
}
