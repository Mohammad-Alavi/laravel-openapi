<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

interface MultipleOf
{
    public function multipleOf(float $value): static;
}
