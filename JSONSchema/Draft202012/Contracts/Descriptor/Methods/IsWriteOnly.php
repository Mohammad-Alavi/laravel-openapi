<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods;

interface IsWriteOnly
{
    public function writeOnly(bool $value): static;
}
