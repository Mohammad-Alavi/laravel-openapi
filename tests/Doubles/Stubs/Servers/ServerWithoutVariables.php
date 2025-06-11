<?php

namespace Tests\Doubles\Stubs\Servers;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ServerFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\URL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;

class ServerWithoutVariables extends ServerFactory
{
    public function build(): Server
    {
        return Server::create(URL::create('https://example.com'))
            ->description(Description::create('sample_description'));
    }
}
