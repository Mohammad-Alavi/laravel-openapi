<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\Implementations;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect\Draft202012;

trait MultipleOf
{
    private \MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MultipleOf|null $multipleOf = null;

    public function multipleOf(float $value): Descriptor
    {
        $clone = clone $this;

        $clone->multipleOf = Draft202012::multipleOf($value);

        return $clone;
    }
}
