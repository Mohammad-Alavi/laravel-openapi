<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptors;

use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Format;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Formats\NumberFormat;

final class NumberDescriptor extends NumeralDescriptor
{
    public function format(NumberFormat $numberFormat): self
    {
        $clone = clone $this;

        $clone->format = Format::create($numberFormat);

        return $clone;
    }

    public static function create(): self
    {
        return parent::number();
    }
}
