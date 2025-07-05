<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

interface MaxContains
{
    public function maxContains(int $value): static;
}
