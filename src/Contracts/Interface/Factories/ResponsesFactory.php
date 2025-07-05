<?php

namespace MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;

interface ResponsesFactory
{
    public function build(): Responses;
}
