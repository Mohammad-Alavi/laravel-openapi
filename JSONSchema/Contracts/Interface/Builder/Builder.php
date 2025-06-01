<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\AdditionalProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\AllOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Anchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\AnyOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Comment;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Constant;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\DefaultValue;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Defs;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\DependentRequired;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Deprecated;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Description;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\DynamicAnchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\DynamicRef;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Enum;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Examples;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\ExclusiveMaximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\ExclusiveMinimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Format;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Id;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\IsReadOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\IsWriteOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Items;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\MaxContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Maximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\MaxItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\MaxLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\MaxProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\MinContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Minimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\MinItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\MinLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\MinProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\MultipleOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\OneOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Pattern;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Properties;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Ref;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Required;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Schema;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Title;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\UnevaluatedItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\UnevaluatedProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\UniqueItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Vocabulary;

interface Builder extends \JsonSerializable, Anchor, Comment, Defs, DynamicAnchor, DynamicRef, Id, Ref, Schema, Vocabulary, UnevaluatedItems, UnevaluatedProperties, ExclusiveMaximum, ExclusiveMinimum, Format, Maximum, MaxLength, Minimum, MinLength, MultipleOf, Pattern, Type, MaxContains, MinContains, UniqueItems, MaxItems, MinItems, Items, AllOf, AnyOf, OneOf, AdditionalProperties, Properties, DependentRequired, MaxProperties, MinProperties, Required, DefaultValue, Deprecated, Description, Examples, IsReadOnly, IsWriteOnly, Title, Constant, Enum
{
}
