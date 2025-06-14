<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

interface Deprecated
{
    public function deprecated(bool $value): static;
}
