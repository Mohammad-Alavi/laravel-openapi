<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

interface MaxLength
{
    public function maxLength(int $value): static;
}
