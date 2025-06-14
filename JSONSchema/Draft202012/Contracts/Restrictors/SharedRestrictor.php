<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\AllOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Anchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\AnyOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Comment;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\DefaultValue;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Defs;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Deprecated;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Description;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\DynamicAnchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\DynamicRef;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Examples;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Id;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\IsReadOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\IsWriteOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\OneOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Ref;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Schema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Title;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Vocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictor;

interface SharedRestrictor extends Restrictor, Anchor, Comment, Defs, DynamicAnchor, DynamicRef, Id, Ref, Schema, Vocabulary, AllOf, AnyOf, OneOf, DefaultValue, Deprecated, Description, Examples, IsReadOnly, IsWriteOnly, Title
{
}
