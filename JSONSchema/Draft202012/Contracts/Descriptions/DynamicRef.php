<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

interface DynamicRef
{
    public function dynamicRef(string $uri): static;
}
