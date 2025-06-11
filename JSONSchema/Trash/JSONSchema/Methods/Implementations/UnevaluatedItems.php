<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\Implementations;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect\Draft202012;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\UnevaluatedItems as UnevaluatedItemsKeyword;

trait UnevaluatedItems
{
    private UnevaluatedItemsKeyword|null $unevaluatedItems = null;

    public function unevaluatedItems(Descriptor $descriptor): Descriptor
    {
        $clone = clone $this;

        $clone->unevaluatedItems = Draft202012::unevaluatedItems($descriptor);

        return $clone;
    }
}
