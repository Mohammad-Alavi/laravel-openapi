<?php

namespace MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;

abstract class ServerFactory
{
    abstract public function build(): Server;
}
