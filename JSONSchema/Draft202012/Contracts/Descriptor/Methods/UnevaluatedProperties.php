<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;

interface UnevaluatedProperties
{
    public function unevaluatedProperties(Descriptor $descriptor): static;
}
