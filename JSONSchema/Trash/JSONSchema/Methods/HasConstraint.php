<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods;

use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\TypeNarrower;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\VocabularyNarrower;

interface HasConstraint
{
    public function vocabularies(): VocabularyNarrower;

    public function typeConstraint(): TypeNarrower;
}
