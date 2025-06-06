<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\Schema\Header;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\JSONSchema;

interface SchemaHeaderRule
{
    public function schema(JSONSchema $schema): SchemaHeaderStyleRule;
}
