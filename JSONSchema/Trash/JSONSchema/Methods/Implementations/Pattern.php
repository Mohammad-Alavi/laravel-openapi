<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\Implementations;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect\Draft202012;

trait Pattern
{
    private \MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Pattern|null $pattern = null;

    public function pattern(string $value): Descriptor
    {
        $clone = clone $this;

        $clone->pattern = Draft202012::pattern($value);

        return $clone;
    }
}
