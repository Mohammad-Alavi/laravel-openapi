<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\Schema\Cookie;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\JSONSchema;

interface SchemaCookieRule
{
    public function schema(JSONSchema $schema): SchemaCookieStyleRule;
}
