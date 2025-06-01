<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\Implementations;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect\Draft202012;

trait Schema
{
    private \MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Schema|null $schema = null;

    public function schema(string $uri): Descriptor
    {
        $clone = clone $this;

        $clone->schema = Draft202012::schema($uri);

        return $clone;
    }
}
