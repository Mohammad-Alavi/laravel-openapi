<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Defs\Def;

interface Defs
{
    public function defs(Def ...$def): static;
}
