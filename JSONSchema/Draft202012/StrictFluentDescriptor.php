<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\ArrayRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\BooleanRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\ConstantRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\EnumRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\IntegerRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\NullRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\NumberRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\ObjectRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\StringRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;

class StrictFluentDescriptor extends LooseFluentDescriptor implements NullRestrictor, BooleanRestrictor, StringRestrictor, IntegerRestrictor, NumberRestrictor, ObjectRestrictor, ArrayRestrictor, ConstantRestrictor, EnumRestrictor
{
    public static function null(): NullRestrictor
    {
        return parent::withoutSchema()->type(Type::null());
    }

    public static function boolean(): BooleanRestrictor
    {
        return parent::withoutSchema()->type(Type::boolean());
    }

    public static function string(): StringRestrictor
    {
        return parent::withoutSchema()->type(Type::string());
    }

    public static function integer(): IntegerRestrictor
    {
        return parent::withoutSchema()->type(Type::integer());
    }

    public static function number(): NumberRestrictor
    {
        return parent::withoutSchema()->type(Type::number());
    }

    public static function object(): ObjectRestrictor
    {
        return parent::withoutSchema()->type(Type::object());
    }

    public static function array(): ArrayRestrictor
    {
        return parent::withoutSchema()->type(Type::array());
    }

    public static function constant(mixed $value): ConstantRestrictor
    {
        return parent::withoutSchema()->const($value);
    }

    public static function enumerator(mixed ...$value): EnumRestrictor
    {
        return parent::withoutSchema()->enum(...$value);
    }
}
