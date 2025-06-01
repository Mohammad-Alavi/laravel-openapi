<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\TypeConstraints;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Anchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Comment;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Defs;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\DynamicAnchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\DynamicRef;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\ExclusiveMaximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\ExclusiveMinimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Id;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Maximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Minimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\MultipleOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Ref;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Schema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Vocabulary;

interface NumeralConstraint extends Anchor, Comment, Defs, DynamicAnchor, DynamicRef, Id, Ref, Schema, Vocabulary, ExclusiveMaximum, ExclusiveMinimum, Maximum, Minimum, MultipleOf
{
}
