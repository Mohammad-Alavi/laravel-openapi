<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

interface MinItems
{
    public function minItems(int $value): static;
}
