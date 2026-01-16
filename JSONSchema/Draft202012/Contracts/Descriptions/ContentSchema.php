<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchemaFactory;

interface ContentSchema
{
    public function contentSchema(JSONSchema|JSONSchemaFactory $schema): static;
}
