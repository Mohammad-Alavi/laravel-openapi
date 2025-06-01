<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\Implementations;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect\Draft202012;

trait DynamicRef
{
    private \MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DynamicRef|null $dynamicRef = null;

    public function dynamicRef(string $uri): Descriptor
    {
        $clone = clone $this;

        $clone->dynamicRef = Draft202012::dynamicRef($uri);

        return $clone;
    }
}
