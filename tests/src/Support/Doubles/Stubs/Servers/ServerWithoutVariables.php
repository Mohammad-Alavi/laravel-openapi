<?php

namespace Tests\src\Support\Doubles\Stubs\Servers;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\ServerFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\URL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;

class ServerWithoutVariables implements ServerFactory
{
    public function build(): Server
    {
        return Server::create(URL::create('https://laragen.io'))
            ->description(Description::create('sample_description'));
    }
}
