<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

interface Examples
{
    public function examples(mixed ...$example): static;
}
