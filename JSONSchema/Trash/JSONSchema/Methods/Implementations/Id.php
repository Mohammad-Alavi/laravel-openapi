<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\Implementations;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect\Draft202012;

trait Id
{
    private \MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Id|null $id = null;

    public function id(string $uri): Descriptor
    {
        $clone = clone $this;

        $clone->id = Draft202012::id($uri);

        return $clone;
    }
}
