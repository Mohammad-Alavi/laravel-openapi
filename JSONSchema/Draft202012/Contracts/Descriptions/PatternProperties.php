<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\PatternProperties\PatternProperty;

interface PatternProperties
{
    public function patternProperties(PatternProperty ...$patternProperty): static;
}
