<?php

namespace Tests\src\Support\Doubles\Stubs\Servers;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\ServerFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Variables\VariableEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\ServerVariable;

class ServerWithVariables implements ServerFactory
{
    public function build(): Server
    {
        return Server::create('https://laragen.io')
            ->description('sample_description')
            ->variables(
                VariableEntry::create(
                    'variable_name',
                    ServerVariable::create('variable_default')
                        ->description('variable_description'),
                ),
            );
    }
}
