<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Dialect;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Builder;
use MohammadAlavi\ObjectOrientedJSONSchema\Formats\DefinedFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\AdditionalProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\AllOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Anchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\AnyOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Comment;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Constant;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\DefaultValue;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Defs\Def;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Defs\Defs;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\DependentRequired\Dependency;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\DependentRequired\DependentRequired;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Deprecated;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Description;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\DynamicAnchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\DynamicRef;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Enum;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Examples;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\ExclusiveMaximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\ExclusiveMinimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Format;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Id;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\IsReadOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\IsWriteOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Items;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\MaxContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Maximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\MaxItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\MaxLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\MaxProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\MinContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Minimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\MinItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\MinLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\MinProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\MultipleOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\OneOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Pattern;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Properties\Properties;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Ref;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Required;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Schema;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Title;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\UnevaluatedItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\UnevaluatedProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\UniqueItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Vocabulary\Vocab;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Vocabulary\Vocabulary;

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

    public static function unevaluatedProperties(Builder $builder): UnevaluatedProperties
    {
        return UnevaluatedProperties::create($builder);
    }

    public static function unevaluatedItems(Builder $builder): UnevaluatedItems
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

    public static function items(Builder $builder): Items
    {
        return Items::create($builder);
    }

    public static function allOf(Builder ...$builder): AllOf
    {
        return AllOf::create(...$builder);
    }

    public static function anyOf(Builder ...$builder): AnyOf
    {
        return AnyOf::create(...$builder);
    }

    public static function oneOf(Builder ...$builder): OneOf
    {
        return OneOf::create(...$builder);
    }

    public static function additionalProperties(Builder|bool $schema): AdditionalProperties
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
