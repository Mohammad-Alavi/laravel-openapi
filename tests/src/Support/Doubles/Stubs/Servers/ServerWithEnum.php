<?php

namespace Tests\src\Support\Doubles\Stubs\Servers;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ServerFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\URL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Variables\VariableEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\Fields\DefaultValue;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\Fields\Description as ServerVariableDescription;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\Fields\Enum;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\ServerVariable;

class ServerWithEnum extends ServerFactory
{
    public function build(): Server
    {
        return Server::create(URL::create('https://example.com'))
            ->description(Description::create('sample_description'))
            ->variables(
                VariableEntry::create(
                    'variable_name',
                    ServerVariable::create(
                        DefaultValue::create('B'),
                    )->description(ServerVariableDescription::create('variable_description'))
                        ->enum(Enum::create('A', 'B', 'C')),
                ),
            );
    }
}
