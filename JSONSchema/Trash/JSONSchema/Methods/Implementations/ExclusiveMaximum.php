<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\Implementations;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect\Draft202012;

trait ExclusiveMaximum
{
    private \MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\ExclusiveMaximum|null $exclusiveMaximum = null;

    public function exclusiveMaximum(float $value): Descriptor
    {
        $clone = clone $this;

        $clone->exclusiveMaximum = Draft202012::exclusiveMaximum($value);

        return $clone;
    }
}
