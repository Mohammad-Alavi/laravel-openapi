<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;

interface UnevaluatedProperties
{
    public function unevaluatedProperties(JSONSchema $descriptor): static;
}
