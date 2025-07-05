<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

interface UniqueItems
{
    public function uniqueItems(bool $value = true): static;
}
