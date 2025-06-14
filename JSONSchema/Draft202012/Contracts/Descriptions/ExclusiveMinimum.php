<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

interface ExclusiveMinimum
{
    public function exclusiveMinimum(float $value): static;
}
