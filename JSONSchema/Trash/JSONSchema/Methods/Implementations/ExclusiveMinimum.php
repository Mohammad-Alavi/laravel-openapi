<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\Implementations;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect\Draft202012;

trait ExclusiveMinimum
{
    private \MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\ExclusiveMinimum|null $exclusiveMinimum = null;

    public function exclusiveMinimum(float $value): Descriptor
    {
        $clone = clone $this;

        $clone->exclusiveMinimum = Draft202012::exclusiveMinimum($value);

        return $clone;
    }
}
