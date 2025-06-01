<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\Implementations;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect\Draft202012;

trait Comment
{
    private \MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Comment|null $comment = null;

    public function comment(string $value): Descriptor
    {
        $clone = clone $this;

        $clone->comment = Draft202012::comment($value);

        return $clone;
    }
}
