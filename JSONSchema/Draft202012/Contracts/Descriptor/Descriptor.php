<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\AdditionalProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\AllOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Anchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\AnyOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Comment;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Constant;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\DefaultValue;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Defs;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\DependentRequired;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Deprecated;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Description;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\DynamicAnchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\DynamicRef;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Enum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Examples;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\ExclusiveMaximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\ExclusiveMinimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Format;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Id;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\IsReadOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\IsWriteOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Items;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\MaxContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Maximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\MaxItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\MaxLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\MaxProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\MinContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Minimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\MinItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\MinLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\MinProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\MultipleOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\OneOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Pattern;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Properties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Ref;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Required;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Schema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Title;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\UnevaluatedItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\UnevaluatedProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\UniqueItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Vocabulary;

interface Descriptor extends \JsonSerializable, Anchor, Comment, Defs, DynamicAnchor, DynamicRef, Id, Ref, Schema, Vocabulary, UnevaluatedItems, UnevaluatedProperties, ExclusiveMaximum, ExclusiveMinimum, Format, Maximum, MaxLength, Minimum, MinLength, MultipleOf, Pattern, Type, MaxContains, MinContains, UniqueItems, MaxItems, MinItems, Items, AllOf, AnyOf, OneOf, AdditionalProperties, Properties, DependentRequired, MaxProperties, MinProperties, Required, DefaultValue, Deprecated, Description, Examples, IsReadOnly, IsWriteOnly, Title, Constant, Enum
{
}
