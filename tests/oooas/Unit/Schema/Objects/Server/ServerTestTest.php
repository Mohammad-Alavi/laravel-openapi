<?php

namespace Tests\oooas\Unit\Schema\Objects\Server;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\URL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Variables\Variable;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Variables\Variables;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\Fields\DefaultValue;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\ServerVariable;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\UnitTestCase;

#[CoversClass(Server::class)]
class ServerTestTest extends UnitTestCase
{
    public function testCreateWithAllParametersWorks(): void
    {
        $serverVariable = ServerVariable::create(DefaultValue::create('Default value'));

        $server = Server::create(URL::create('https://api.example.con/v1'))
            ->description(Description::create('Core API'))
            ->variables(
                Variables::create(
                    Variable::create('ServerVariableName', $serverVariable),
                ),
            );

        $this->assertSame([
            'url' => 'https://api.example.con/v1',
            'description' => 'Core API',
            'variables' => [
                'ServerVariableName' => [
                    'default' => 'Default value',
                ],
            ],
        ], $server->asArray());
    }

    public function testVariablesAreSupported(): void
    {
        $serverVariable = ServerVariable::create(DefaultValue::create('demo'));

        $server = Server::create(URL::create('https://api.example.con/v1'))
            ->variables(
                Variables::create(
                    Variable::create('username', $serverVariable),
                ),
            );

        $this->assertSame(
            [
                'url' => 'https://api.example.con/v1',
                'variables' => [
                    'username' => [
                        'default' => 'demo',
                    ],
                ],
            ],
            $server->asArray(),
        );
    }
}
