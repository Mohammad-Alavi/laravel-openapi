<?php

namespace MohammadAlavi\LaravelOpenApi\Contracts\Factories;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;

interface ResponsesFactory
{
    public function build(): Responses;
}
