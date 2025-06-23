<?php

namespace MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;

interface ServerFactory
{
    public function build(): Server;
}
