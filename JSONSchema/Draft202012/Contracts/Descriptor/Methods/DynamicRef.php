<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods;

interface DynamicRef
{
    public function dynamicRef(string $uri): static;
}
