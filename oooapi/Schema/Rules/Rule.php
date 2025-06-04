<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Rules;

interface Rule
{
    public static function validate(string $value): void;
}
