<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\DefinedFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\AdditionalProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\AllOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Anchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\AnyOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Comment;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Constant;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DefaultValue;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Defs\Def;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Defs\Defs;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DependentRequired\Dependency;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DependentRequired\DependentRequired;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Deprecated;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Description;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DynamicAnchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DynamicRef;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Enum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Examples;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\ExclusiveMaximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\ExclusiveMinimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Format;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Id;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\IsReadOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\IsWriteOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Items;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MaxContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Maximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MaxItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MaxLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MaxProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MinContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Minimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MinItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MinLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MinProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MultipleOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\OneOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Pattern;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Properties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Ref;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Required;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Schema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Title;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\UnevaluatedItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\UnevaluatedProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\UniqueItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Vocabulary\Vocab;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Vocabulary\Vocabulary;

final readonly class Draft202012
{
    public static function id(string $uri): Id
    {
        return Id::create($uri);
    }

    public static function schema(string $uri): Schema
    {
        return Schema::create($uri);
    }

    public static function ref(string $value): Ref
    {
        return Ref::create($value);
    }

    public static function comment(string $value): Comment
    {
        return Comment::create($value);
    }

    public static function defs(Def ...$def): Defs
    {
        return Defs::create(...$def);
    }

    public static function anchor(string $value): Anchor
    {
        return Anchor::create($value);
    }

    public static function dynamicAnchor(string $value): DynamicAnchor
    {
        return DynamicAnchor::create($value);
    }

    public static function dynamicRef(string $value): DynamicRef
    {
        return DynamicRef::create($value);
    }

    public static function vocabulary(Vocab ...$vocab): Vocabulary
    {
        return Vocabulary::create(...$vocab);
    }

    public static function unevaluatedProperties(Descriptor $builder): UnevaluatedProperties
    {
        return UnevaluatedProperties::create($builder);
    }

    public static function unevaluatedItems(Descriptor $builder): UnevaluatedItems
    {
        return UnevaluatedItems::create($builder);
    }

    public static function format(DefinedFormat $definedFormat): Format
    {
        return Format::create($definedFormat);
    }

    public static function type(Type|string ...$type): Type
    {
        return Type::create(...$type);
    }

    public static function maxLength(int $value): MaxLength
    {
        return MaxLength::create($value);
    }

    public static function minLength(int $value): MinLength
    {
        return MinLength::create($value);
    }

    public static function pattern(string $value): Pattern
    {
        return Pattern::create($value);
    }

    public static function exclusiveMaximum(float $value): ExclusiveMaximum
    {
        return ExclusiveMaximum::create($value);
    }

    public static function exclusiveMinimum(float $value): ExclusiveMinimum
    {
        return ExclusiveMinimum::create($value);
    }

    public static function maximum(float $value): Maximum
    {
        return Maximum::create($value);
    }

    public static function minimum(float $value): Minimum
    {
        return Minimum::create($value);
    }

    public static function multipleOf(float $value): MultipleOf
    {
        return MultipleOf::create($value);
    }

    public static function maxContains(int $value): MaxContains
    {
        return MaxContains::create($value);
    }

    public static function maxItems(int $value): MaxItems
    {
        return MaxItems::create($value);
    }

    public static function minContains(int $value): MinContains
    {
        return MinContains::create($value);
    }

    public static function minItems(int $value): MinItems
    {
        return MinItems::create($value);
    }

    public static function uniqueItems(bool $value): UniqueItems
    {
        return UniqueItems::create($value);
    }

    public static function items(Descriptor $builder): Items
    {
        return Items::create($builder);
    }

    public static function allOf(Descriptor ...$builder): AllOf
    {
        return AllOf::create(...$builder);
    }

    public static function anyOf(Descriptor ...$builder): AnyOf
    {
        return AnyOf::create(...$builder);
    }

    public static function oneOf(Descriptor ...$builder): OneOf
    {
        return OneOf::create(...$builder);
    }

    public static function additionalProperties(Descriptor|bool $schema): AdditionalProperties
    {
        return AdditionalProperties::create($schema);
    }

    public static function properties(Property ...$property): Properties
    {
        return Properties::create(...$property);
    }

    public static function dependentRequired(Dependency ...$dependency): DependentRequired
    {
        return DependentRequired::create(...$dependency);
    }

    public static function maxProperties(int $value): MaxProperties
    {
        return MaxProperties::create($value);
    }

    public static function minProperties(int $value): MinProperties
    {
        return MinProperties::create($value);
    }

    public static function required(string ...$property): Required
    {
        return Required::create(...$property);
    }

    public static function default(mixed $value): DefaultValue
    {
        return DefaultValue::create($value);
    }

    public static function deprecated(bool $value): Deprecated
    {
        return Deprecated::create($value);
    }

    public static function description(string $value): Description
    {
        return Description::create($value);
    }

    public static function examples(mixed ...$example): Examples
    {
        return Examples::create(...$example);
    }

    public static function readOnly(bool $value): IsReadOnly
    {
        return IsReadOnly::create($value);
    }

    public static function writeOnly(bool $value): IsWriteOnly
    {
        return IsWriteOnly::create($value);
    }

    public static function title(string $value): Title
    {
        return Title::create($value);
    }

    public static function const(mixed $value): Constant
    {
        return Constant::create($value);
    }

    public static function enum(mixed ...$value): Enum
    {
        return Enum::create(...$value);
    }
}
