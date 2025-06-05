<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptors;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Format;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Formats\NumberFormat;

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
