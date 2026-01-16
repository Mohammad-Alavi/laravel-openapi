<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DependentSchemas\DependentSchema;

interface DependentSchemas
{
    public function dependentSchemas(DependentSchema ...$dependentSchema): static;
}
