<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

interface Ref
{
    public function ref(string $uri): static;
}
