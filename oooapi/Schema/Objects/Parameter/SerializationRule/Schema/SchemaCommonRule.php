<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\Schema;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\CommonFields;

interface SchemaCommonRule extends CommonFields
{
    public function example(Example $example): static;

    public function examples(Example ...$example): static;
}
