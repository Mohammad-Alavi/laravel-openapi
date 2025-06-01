<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods;

interface ExclusiveMinimum
{
    public function exclusiveMinimum(float $value): static;
}
