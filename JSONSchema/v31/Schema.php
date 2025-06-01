<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\v31;

use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\SchemaBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\ArrayBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\BooleanBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\ConstantBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\EnumBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\IntegerBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\NullBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\NumberBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\ObjectBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\StringBuilder;

final class Schema extends SchemaBuilder implements NullBuilder, BooleanBuilder, StringBuilder, IntegerBuilder, NumberBuilder, ObjectBuilder, ArrayBuilder, ConstantBuilder, EnumBuilder
{
    public static function null(): NullBuilder
    {
        return parent::create()->type(Type::null());
    }

    public static function boolean(): BooleanBuilder
    {
        return parent::create()->type(Type::boolean());
    }

    public static function string(): StringBuilder
    {
        return parent::create()->type(Type::string());
    }

    public static function integer(): IntegerBuilder
    {
        return parent::create()->type(Type::integer());
    }

    public static function number(): NumberBuilder
    {
        return parent::create()->type(Type::number());
    }

    public static function object(): ObjectBuilder
    {
        return parent::create()->type(Type::object());
    }

    public static function array(): ArrayBuilder
    {
        return parent::create()->type(Type::array());
    }

    public static function constant(mixed $value): ConstantBuilder
    {
        return parent::create()->const($value);
    }

    public static function enumerator(mixed ...$value): EnumBuilder
    {
        return parent::create()->enum(...$value);
    }
}
