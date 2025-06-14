<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;

interface UnevaluatedItems
{
    public function unevaluatedItems(JSONSchema $descriptor): static;
}
