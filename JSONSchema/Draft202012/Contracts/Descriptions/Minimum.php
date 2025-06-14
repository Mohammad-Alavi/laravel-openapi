<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

interface Minimum
{
    public function minimum(float $value): static;
}
