<?php

namespace Tests\oooas\Unit\Schema\Objects\Server;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Variables\VariableEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\ServerVariable;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\UnitTestCase;

#[CoversClass(Server::class)]
class ServerTestTest extends UnitTestCase
{
    public function testCreateWithAllParametersWorks(): void
    {
        $serverVariable = ServerVariable::create('Default value');

        $server = Server::create('https://api.example.con/v1')
            ->description('Core API')
            ->variables(
                VariableEntry::create('ServerVariableName', $serverVariable),
            );

        $this->assertSame([
            'url' => 'https://api.example.con/v1',
            'description' => 'Core API',
            'variables' => [
                'ServerVariableName' => [
                    'default' => 'Default value',
                ],
            ],
        ], $server->compile());
    }

    public function testVariablesAreSupported(): void
    {
        $serverVariable = ServerVariable::create('demo');

        $server = Server::create('https://api.example.con/v1')
            ->variables(
                VariableEntry::create('username', $serverVariable),
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
            $server->compile(),
        );
    }

    public function testNameFieldWorks(): void
    {
        $server = Server::create('https://api.example.com/v1')
            ->name('Production Server');

        $this->assertSame([
            'url' => 'https://api.example.com/v1',
            'name' => 'Production Server',
        ], $server->compile());
    }

    public function testNameFieldWithOtherParameters(): void
    {
        $server = Server::create('https://api.example.com/v1')
            ->name('Production API')
            ->description('Main production server');

        $this->assertSame([
            'url' => 'https://api.example.com/v1',
            'name' => 'Production API',
            'description' => 'Main production server',
        ], $server->compile());
    }
}
