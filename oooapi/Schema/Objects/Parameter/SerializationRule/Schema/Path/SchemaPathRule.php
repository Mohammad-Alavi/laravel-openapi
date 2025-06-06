<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\Schema\Path;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\JSONSchema;

interface SchemaPathRule
{
    public function schema(JSONSchema $schema): SchemaPathStyleRule;
}
