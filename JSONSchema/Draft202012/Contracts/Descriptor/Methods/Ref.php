<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods;

interface Ref
{
    public function ref(string $uri): static;
}
