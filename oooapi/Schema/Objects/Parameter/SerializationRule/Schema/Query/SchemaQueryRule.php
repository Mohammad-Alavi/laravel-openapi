<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\Schema\Query;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\JSONSchema;

interface SchemaQueryRule
{
    public function schema(JSONSchema $schema): SchemaQueryStyleRule;
}
