<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\Implementations;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect\Draft202012;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;

trait Format
{
    private \MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Format|null $format = null;

    public function format(StringFormat $stringFormat): Descriptor
    {
        $clone = clone $this;

        $clone->format = Draft202012::format($stringFormat);

        return $clone;
    }
}
