<?php

namespace MohammadAlavi\LaravelOpenApi\Factories;

use MohammadAlavi\ObjectOrientedOAS\Objects\Server;

abstract class ServerFactory
{
    abstract public function build(): Server;
}
