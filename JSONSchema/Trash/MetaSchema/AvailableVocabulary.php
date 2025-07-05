<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\MetaSchema;

use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Vocabulary;

interface AvailableVocabulary
{
    public function id(): string;

    public function vocabulary(): Vocabulary;

    public function required(): bool;
}
