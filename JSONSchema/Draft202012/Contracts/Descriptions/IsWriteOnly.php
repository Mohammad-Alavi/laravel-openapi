<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

interface IsWriteOnly
{
    public function writeOnly(bool $value): static;
}
