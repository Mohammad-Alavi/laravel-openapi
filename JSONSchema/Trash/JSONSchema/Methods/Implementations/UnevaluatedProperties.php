<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\Implementations;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect\Draft202012;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\UnevaluatedProperties as UnevaluatedPropertiesKeyword;

trait UnevaluatedProperties
{
    private UnevaluatedPropertiesKeyword|null $unevaluatedProperties = null;

    public function unevaluatedProperties(Descriptor $descriptor): Descriptor
    {
        $clone = clone $this;

        $clone->unevaluatedProperties = Draft202012::unevaluatedProperties($descriptor);

        return $clone;
    }
}
