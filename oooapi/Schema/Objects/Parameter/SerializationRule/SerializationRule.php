<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule;

interface SerializationRule extends \JsonSerializable
{
    public function jsonSerialize(): array;
}
