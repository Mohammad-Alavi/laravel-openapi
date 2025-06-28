<?php

namespace Tests\src\Support\Doubles\Stubs\Servers;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\ServerFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\URL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Variables\VariableEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\Fields\DefaultValue;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\Fields\Enum;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\ServerVariable;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;

class ServerWithEnum implements ServerFactory
{
    public function build(): Server
    {
        return Server::create(URL::create('https://laragen.io'))
            ->description(Description::create('sample_description'))
            ->variables(
                VariableEntry::create(
                    'variable_name',
                    ServerVariable::create(
                        DefaultValue::create('B'),
                    )->description(Description::create('variable_description'))
                        ->enum(Enum::create('A', 'B', 'C')),
                ),
            );
    }
}
