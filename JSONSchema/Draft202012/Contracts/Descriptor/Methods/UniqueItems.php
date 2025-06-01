<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods;

interface UniqueItems
{
    public function uniqueItems(bool $value = true): static;
}
