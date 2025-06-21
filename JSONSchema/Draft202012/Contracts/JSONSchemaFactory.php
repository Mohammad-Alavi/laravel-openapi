<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts;

interface JSONSchemaFactory
{
    public function build(): JSONSchema;
}
