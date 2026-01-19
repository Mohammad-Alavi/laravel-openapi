<?php

namespace MohammadAlavi\LaravelOpenApi\Contracts\Factories;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;

interface ServerFactory
{
    public function build(): Server;
}
