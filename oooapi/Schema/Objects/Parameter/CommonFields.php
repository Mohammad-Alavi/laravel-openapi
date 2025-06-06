<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\CommonFields\Description;

interface CommonFields
{
    public function description(Description $description): static;

    public function required(): static;

    public function deprecated(): static;

    public function allowEmptyValue(): static;
}
