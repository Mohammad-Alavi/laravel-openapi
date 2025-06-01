<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\Implementations;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect\Draft202012;

trait Type
{
    private \MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type|null $type = null;

    public function type(string ...$type): Descriptor
    {
        $clone = clone $this;

        $clone->type = Draft202012::type(...$type);

        return $clone;
    }
}
