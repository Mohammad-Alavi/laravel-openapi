<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods;

interface DynamicAnchor
{
    public function dynamicAnchor(string $value): static;
}
