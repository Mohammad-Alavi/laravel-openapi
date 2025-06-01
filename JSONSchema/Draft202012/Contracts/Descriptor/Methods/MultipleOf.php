<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods;

interface MultipleOf
{
    public function multipleOf(float $value): static;
}
