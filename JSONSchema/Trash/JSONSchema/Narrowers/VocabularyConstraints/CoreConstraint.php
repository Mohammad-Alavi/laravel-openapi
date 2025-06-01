<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\VocabularyConstraints;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Anchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Comment;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Defs;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\DynamicAnchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\DynamicRef;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Id;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Ref;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Schema;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Vocabulary;

interface CoreConstraint extends Anchor, Comment, Defs, DynamicAnchor, DynamicRef, Id, Ref, Schema, Vocabulary
{
}
