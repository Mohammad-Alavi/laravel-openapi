<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;

interface Properties
{
    public function properties(Property ...$property): static;
}
