<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

interface ExclusiveMaximum
{
    public function exclusiveMaximum(float $value): static;
}
