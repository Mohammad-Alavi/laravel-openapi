<?php

namespace Tests\Doubles\Stubs\Servers;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\ServerFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\URL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Variables\VariableEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\Fields\DefaultValue;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\Fields\Description as ServerVarDescription;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\Fields\Enum;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\ServerVariable;

class ServerWithMultipleVariableFormatting extends ServerFactory
{
    public function build(): Server
    {
        return Server::create(URL::create('https://example.com'))
            ->description(Description::create('sample_description'))
            ->variables(
                VariableEntry::create(
                    'ServerVariableA',
                    ServerVariable::create(
                        DefaultValue::create('B'),
                    )->description(ServerVarDescription::create('variable_description'))
                        ->enum(Enum::create('A', 'B')),
                ),
                VariableEntry::create(
                    'ServerVariableB',
                    ServerVariable::create(
                        DefaultValue::create('sample'),
                    )->description(
                        ServerVarDescription::create('sample_description'),
                    ),
                ),
            );
    }
}
