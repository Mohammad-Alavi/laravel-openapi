<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\AdditionalProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\AllOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Anchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\AnyOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Comment;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Constant;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Contains;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\ContentEncoding;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\ContentMediaType;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\ContentSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\DefaultValue;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Defs;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\DependentRequired;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\DependentSchemas;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Deprecated;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Description;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\DynamicAnchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\DynamicRef;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Enum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Examples;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\ExclusiveMaximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\ExclusiveMinimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Format;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Id;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\IsReadOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\IsWriteOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Items;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\MaxContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Maximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\MaxItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\MaxLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\MaxProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\MinContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Minimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\MinItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\MinLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\MinProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\MultipleOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\OneOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Pattern;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\PatternProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\PrefixItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Properties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\PropertyNames;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Ref;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Required;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Schema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Title;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\UnevaluatedItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\UnevaluatedProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\UniqueItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Vocabulary;

interface FluentDescriptor extends JSONSchema, Compilable, \JsonSerializable,
    // Core vocabulary
    Anchor, Comment, Defs, DynamicAnchor, DynamicRef, Id, Ref, Schema, Vocabulary,
    // Applicator vocabulary
    AllOf, AnyOf, OneOf, PrefixItems, Items, Contains, Properties, PatternProperties, AdditionalProperties, PropertyNames, DependentSchemas,
    // Unevaluated vocabulary
    UnevaluatedItems, UnevaluatedProperties,
    // Validation vocabulary
    Type, Constant, Enum, MaxLength, MinLength, Pattern, Maximum, ExclusiveMaximum, Minimum, ExclusiveMinimum, MultipleOf, MaxItems, MinItems, UniqueItems, MaxContains, MinContains, MaxProperties, MinProperties, Required, DependentRequired,
    // Meta-data vocabulary
    Title, Description, DefaultValue, Deprecated, IsReadOnly, IsWriteOnly, Examples,
    // Format vocabulary
    Format,
    // Content vocabulary
    ContentEncoding, ContentMediaType, ContentSchema
{
}
