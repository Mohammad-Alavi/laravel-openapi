<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

interface MinLength
{
    public function minLength(int $value): static;
}
