<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\Implementations;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect\Draft202012;

trait DynamicAnchor
{
    private \MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DynamicAnchor|null $dynamicAnchor = null;

    public function dynamicAnchor(string $value): Descriptor
    {
        $clone = clone $this;

        $clone->dynamicAnchor = Draft202012::dynamicAnchor($value);

        return $clone;
    }
}
