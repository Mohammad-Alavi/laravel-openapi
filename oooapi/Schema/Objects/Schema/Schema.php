<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\ArrayRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\BooleanRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\ConstantRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\EnumRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\IntegerRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\NullRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\NumberRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\ObjectRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\SharedRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\StringRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\StrictFluentDescriptor;

final class Schema
{
    public static function null(): NullRestrictor
    {
        return StrictFluentDescriptor::withoutSchema()->type(Type::null());
    }

    public static function boolean(): BooleanRestrictor
    {
        return StrictFluentDescriptor::withoutSchema()->type(Type::boolean());
    }

    public static function string(): StringRestrictor
    {
        return StrictFluentDescriptor::string();
    }

    public static function integer(): IntegerRestrictor
    {
        return StrictFluentDescriptor::integer();
    }

    public static function number(): NumberRestrictor
    {
        return StrictFluentDescriptor::number();
    }

    public static function object(): ObjectRestrictor
    {
        return StrictFluentDescriptor::object();
    }

    public static function array(): ArrayRestrictor
    {
        return StrictFluentDescriptor::array();
    }

    public static function const(mixed $value): ConstantRestrictor
    {
        return StrictFluentDescriptor::constant($value);
    }

    public static function enum(mixed ...$value): EnumRestrictor
    {
        return StrictFluentDescriptor::enumerator(...$value);
    }

    public static function oneOf(StrictFluentDescriptor ...$schemas): SharedRestrictor
    {
        return StrictFluentDescriptor::withoutSchema()->oneOf(...$schemas);
    }

    public static function anyOf(StrictFluentDescriptor ...$schemas): SharedRestrictor
    {
        return StrictFluentDescriptor::withoutSchema()->anyOf(...$schemas);
    }

    public static function allOf(StrictFluentDescriptor ...$schemas): SharedRestrictor
    {
        return StrictFluentDescriptor::withoutSchema()->allOf(...$schemas);
    }
}
