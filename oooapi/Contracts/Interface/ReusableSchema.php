<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface;

interface ReusableSchema extends Reusable
{
    public static function ref(): string;
}
