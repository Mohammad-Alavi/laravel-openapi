<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

interface DefaultValue
{
    public function default(mixed $value): static;
}
