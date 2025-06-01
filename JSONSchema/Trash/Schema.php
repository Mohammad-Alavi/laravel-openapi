<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash;

// TODO: for schema the $key is required I think. It should bre required when creating it via ny construction method

use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptors\ArrayDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptors\BooleanDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptors\EnumDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptors\IntegerDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptors\NullDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptors\NumberDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptors\ObjectDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptors\StringDescriptor;

abstract class Schema
{
    // TODO: Implement $id from JSON SCHEMA.
    // https://www.learnjsonschema.com/2020-12/core/id/#
    // Also look at OAS spec 3.1
    // public string|null $id = null;

    // TODO: Creating an schema that have multiple types is valid in JSON SCHEMA. Implement it.
    // How does the non-overlapping type specific validation work?
    // For example, if the type is integer and string, how does the minLength validation work?

    public static function null(): NullDescriptor
    {
        return NullDescriptor::create();
    }

    public static function boolean(): BooleanDescriptor
    {
        return BooleanDescriptor::create();
    }

    public static function string(): StringDescriptor
    {
        return StringDescriptor::create();
    }

    public static function integer(): IntegerDescriptor
    {
        return IntegerDescriptor::create();
    }

    public static function number(): NumberDescriptor
    {
        return NumberDescriptor::create();
    }

    public static function object(): ObjectDescriptor
    {
        return ObjectDescriptor::create();
    }

    public static function array(): ArrayDescriptor
    {
        return ArrayDescriptor::create();
    }

    public static function enum(...$values): EnumDescriptor
    {
        return EnumDescriptor::create(...$values);
    }
}
