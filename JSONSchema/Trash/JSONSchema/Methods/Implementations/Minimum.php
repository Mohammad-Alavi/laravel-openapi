<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\Implementations;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect\Draft202012;

trait Minimum
{
    private \MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Minimum|null $minimum = null;

    public function minimum(float $value): Descriptor
    {
        $clone = clone $this;

        $clone->minimum = Draft202012::minimum($value);

        return $clone;
    }
}
