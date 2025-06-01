<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\AllOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Anchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\AnyOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Comment;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\DefaultValue;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Defs;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Deprecated;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Description;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\DynamicAnchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\DynamicRef;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Examples;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Id;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\IsReadOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\IsWriteOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\OneOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Ref;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Schema;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Title;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Vocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\JSONSchema;

interface SharedBuilder extends JSONSchema, Anchor, Comment, Defs, DynamicAnchor, DynamicRef, Id, Ref, Schema, Vocabulary, AllOf, AnyOf, OneOf, DefaultValue, Deprecated, Description, Examples, IsReadOnly, IsWriteOnly, Title
{
}
