<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\Implementations;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect\Draft202012;

trait MaxLength
{
    private \MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MaxLength|null $maxLength = null;

    public function maxLength(int $value): Descriptor
    {
        $clone = clone $this;

        $clone->maxLength = Draft202012::maxLength($value);

        return $clone;
    }
}
