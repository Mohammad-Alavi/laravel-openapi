<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods;

interface MaxItems
{
    public function maxItems(int $value): static;
}
