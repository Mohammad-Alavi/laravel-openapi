<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods;

interface MinProperties
{
    public function minProperties(int $value): static;
}
