<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\Implementations;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect\Draft202012;

trait Maximum
{
    private \MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Maximum|null $maximum = null;

    public function maximum(float $value): Descriptor
    {
        $clone = clone $this;

        $clone->maximum = Draft202012::maximum($value);

        return $clone;
    }
}
