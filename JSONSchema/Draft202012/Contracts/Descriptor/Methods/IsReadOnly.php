<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods;

interface IsReadOnly
{
    public function readOnly(bool $value): static;
}
