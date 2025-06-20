<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Contracts;

interface Scheme
{
    public function type(): string;

    public function toArray(): array;
}
