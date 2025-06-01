<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods;

interface Id
{
    public function id(string $uri): static;
}
