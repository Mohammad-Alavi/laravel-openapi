<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

interface MaxItems
{
    public function maxItems(int $value): static;
}
