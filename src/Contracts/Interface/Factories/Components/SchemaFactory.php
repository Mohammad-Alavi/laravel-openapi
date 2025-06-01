<?php

namespace MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\Components;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\ComponentFactory;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\JSONSchema;

interface SchemaFactory extends ComponentFactory
{
    public function build(): JSONSchema;
}
