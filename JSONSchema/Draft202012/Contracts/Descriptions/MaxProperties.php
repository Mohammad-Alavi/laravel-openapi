<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

interface MaxProperties
{
    public function maxProperties(int $value): static;
}
