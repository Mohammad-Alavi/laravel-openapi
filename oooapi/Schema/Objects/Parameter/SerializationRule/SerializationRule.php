<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule;

interface SerializationRule
{
    public function toArray(): array;
}
