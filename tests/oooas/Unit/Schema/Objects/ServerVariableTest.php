<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Variables\VariableEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\Fields\DefaultValue;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\Fields\Enum;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\ServerVariable;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\UnitTestCase;

#[CoversClass(ServerVariable::class)]
class ServerVariableTest extends UnitTestCase
{
    public function testCreateWithAllParametersWorks(): void
    {
        $serverVariable = ServerVariable::create(DefaultValue::create('Earth'))
            ->enum(Enum::create('Earth', 'Mars', 'Saturn'))
            ->description(Description::create('The planet the server is running on'));

        $server = Server::default()
            ->variables(
                VariableEntry::create(
                    'ServerVariableName',
                    $serverVariable,
                ),
            );

        $this->assertSame([
            'url' => '/',
            'variables' => [
                'ServerVariableName' => [
                    'enum' => ['Earth', 'Mars', 'Saturn'],
                    'default' => 'Earth',
                    'description' => 'The planet the server is running on',
                ],
            ],
        ], $server->asArray());
    }
}
