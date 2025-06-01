<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods;

interface DefaultValue
{
    public function default(mixed $value): static;
}
