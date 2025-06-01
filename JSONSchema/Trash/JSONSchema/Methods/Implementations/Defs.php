<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\Implementations;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect\Draft202012;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Defs\Def;

trait Defs
{
    private \MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Defs\Defs|null $defs = null;

    public function defs(Def ...$def): Descriptor
    {
        $clone = clone $this;

        $clone->defs = Draft202012::defs(...$def);

        return $clone;
    }
}
