<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\Implementations;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect\Draft202012;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Vocabulary\Vocab;

trait Vocabulary
{
    private \MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Vocabulary\Vocabulary|null $vocabulary = null;

    public function vocabulary(Vocab ...$vocab): Descriptor
    {
        $clone = clone $this;

        $clone->vocabulary = Draft202012::vocabulary(...$vocab);

        return $clone;
    }
}
