<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\Implementations;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect\Draft202012;

trait Anchor
{
    private \MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Anchor|null $anchor = null;

    public function anchor(string $value): Descriptor
    {
        $clone = clone $this;

        $clone->anchor = Draft202012::anchor($value);

        return $clone;
    }
}
