<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\Implementations;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Builder;
use MohammadAlavi\ObjectOrientedJSONSchema\Dialect\Draft202012;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\UnevaluatedProperties as UnevaluatedPropertiesKeyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptor;

trait UnevaluatedProperties
{
    private UnevaluatedPropertiesKeyword|null $unevaluatedProperties = null;

    public function unevaluatedProperties(Descriptor $descriptor): Builder
    {
        $clone = clone $this;

        $clone->unevaluatedProperties = Draft202012::unevaluatedProperties($descriptor);

        return $clone;
    }
}
