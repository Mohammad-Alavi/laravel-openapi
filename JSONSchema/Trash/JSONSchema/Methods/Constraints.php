<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Builder;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\TypeConstraints\IntegerConstraint;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\TypeConstraints\NumberConstraint;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\TypeConstraints\StringConstraint;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\TypeNarrower;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\VocabularyConstraints\ApplicatorConstraint;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\VocabularyConstraints\ContentConstraint;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\VocabularyConstraints\CoreConstraint;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\VocabularyConstraints\FormatAnnotationConstraint;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\VocabularyConstraints\MetaDataConstraint;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\VocabularyConstraints\UnevaluatedConstraint;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\VocabularyConstraints\ValidationConstraint;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\VocabularyNarrower;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Narrowable;

// TODO: Separate each method into a single trait like the way it is done for Keywords
trait Constraints
{
    public function keywords(): Narrowable
    {
        return $this;
    }

    public function all(): Builder
    {
        return $this;
    }

    public function groupedBy(): HasConstraint
    {
        return $this;
    }

    public function typeConstraint(): TypeNarrower
    {
        return $this;
    }

    public function string(): StringConstraint
    {
        return $this->type('string');
    }

    public function integer(): IntegerConstraint
    {
        return $this->type('integer');
    }

    public function number(): NumberConstraint
    {
        return $this->type('number');
    }

    public function vocabularies(): VocabularyNarrower
    {
        return $this;
    }

    public function applicator(): ApplicatorConstraint
    {
        return $this;
    }

    public function content(): ContentConstraint
    {
        return $this;
    }

    public function core(): CoreConstraint
    {
        return $this;
    }

    public function formatAnnotation(): FormatAnnotationConstraint
    {
        return $this;
    }

    public function metaData(): MetaDataConstraint
    {
        return $this;
    }

    public function unevaluated(): UnevaluatedConstraint
    {
        return $this;
    }

    public function validation(): ValidationConstraint
    {
        return $this;
    }
}
