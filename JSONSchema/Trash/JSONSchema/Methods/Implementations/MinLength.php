<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\Implementations;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect\Draft202012;

trait MinLength
{
    private \MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MinLength|null $minLength = null;

    public function minLength(int $value): Descriptor
    {
        $clone = clone $this;

        $clone->minLength = Draft202012::minLength($value);

        return $clone;
    }
}
