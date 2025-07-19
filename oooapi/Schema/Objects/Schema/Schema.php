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
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\RefRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\SharedRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\StringRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\StrictFluentDescriptor;

final class Schema
{
    public static function from(array $data): ObjectRestrictor
    {
        return StrictFluentDescriptor::from($data);
    }

    public static function null(): NullRestrictor
    {
        return StrictFluentDescriptor::null();
    }

    public static function boolean(): BooleanRestrictor
    {
        return StrictFluentDescriptor::boolean();
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

    public static function ref(string $ref): RefRestrictor
    {
        return StrictFluentDescriptor::withoutSchema()->ref($ref);
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
