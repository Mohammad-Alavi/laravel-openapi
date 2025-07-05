<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

interface Enum
{
    public function enum(mixed ...$value): static;
}
