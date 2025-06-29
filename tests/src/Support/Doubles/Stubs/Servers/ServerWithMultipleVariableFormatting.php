<?php

namespace Tests\src\Support\Doubles\Stubs\Servers;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\ServerFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Variables\VariableEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\ServerVariable;

class ServerWithMultipleVariableFormatting implements ServerFactory
{
    public function build(): Server
    {
        return Server::create('https://laragen.io')
            ->description('sample_description')
            ->variables(
                VariableEntry::create(
                    'ServerVariableA',
                    ServerVariable::create(
                        'B',
                    )->description('variable_description')
                        ->enum('A', 'B'),
                ),
                VariableEntry::create(
                    'ServerVariableB',
                    ServerVariable::create(
                        'sample',
                    )->description(
                        'sample_description',
                    ),
                ),
            );
    }
}
