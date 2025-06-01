<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods;

interface MaxLength
{
    public function maxLength(int $value): static;
}
