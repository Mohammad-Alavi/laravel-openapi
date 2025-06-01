<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers;

use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\VocabularyConstraints\ApplicatorConstraint;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\VocabularyConstraints\ContentConstraint;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\VocabularyConstraints\CoreConstraint;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\VocabularyConstraints\FormatAnnotationConstraint;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\VocabularyConstraints\MetaDataConstraint;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\VocabularyConstraints\UnevaluatedConstraint;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\VocabularyConstraints\ValidationConstraint;

interface VocabularyNarrower
{
    public function applicator(): ApplicatorConstraint;

    public function content(): ContentConstraint;

    public function core(): CoreConstraint;

    public function formatAnnotation(): FormatAnnotationConstraint;

    public function metaData(): MetaDataConstraint;

    public function unevaluated(): UnevaluatedConstraint;

    public function validation(): ValidationConstraint;
}
