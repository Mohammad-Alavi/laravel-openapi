<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods;

interface MaxProperties
{
    public function maxProperties(int $value): static;
}
