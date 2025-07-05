<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

interface MinProperties
{
    public function minProperties(int $value): static;
}
