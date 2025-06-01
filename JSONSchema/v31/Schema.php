<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\v31;

use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\ArrayBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\BooleanBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\ConstantBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\EnumBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\IntegerBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\NullBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\NumberBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\ObjectBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\StringBuilder;

final readonly class Schema
{
    public static function null(): NullBuilder
    {
        return FluentSchemaBuilder::create()->null();
    }

    public static function boolean(): BooleanBuilder
    {
        return FluentSchemaBuilder::create()->boolean();
    }

    public static function string(): StringBuilder
    {
        return FluentSchemaBuilder::create()->string();
    }

    public static function integer(): IntegerBuilder
    {
        return FluentSchemaBuilder::create()->integer();
    }

    public static function number(): NumberBuilder
    {
        return FluentSchemaBuilder::create()->number();
    }

    public static function object(): ObjectBuilder
    {
        return FluentSchemaBuilder::create()->object();
    }

    public static function array(): ArrayBuilder
    {
        return FluentSchemaBuilder::create()->array();
    }

    public static function const(mixed $value): ConstantBuilder
    {
        return FluentSchemaBuilder::create()->constant($value);
    }

    public static function enum(mixed ...$value): EnumBuilder
    {
        return FluentSchemaBuilder::create()->enumerator(...$value);
    }
}
