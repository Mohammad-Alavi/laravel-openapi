<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

interface IsReadOnly
{
    public function readOnly(): static;
}
