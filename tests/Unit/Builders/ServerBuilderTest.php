<?php

namespace Tests\Unit\Builders;

use MohammadAlavi\ObjectOrientedOAS\Objects\Server;
use MohammadAlavi\ObjectOrientedOAS\Objects\ServerVariable;
use MohammadAlavi\LaravelOpenApi\Collectors\ServerBuilder;
use MohammadAlavi\LaravelOpenApi\Factories\ServerFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

#[CoversClass(ServerBuilder::class)]
class ServerBuilderTest extends TestCase
{
    #[DataProvider('serverFQCNProvider')]
    public function testCanBuildServerFromFQCN(array $serverFactories, array $expected): void
    {
        $builder = new ServerBuilder();
        $servers = $builder->build($serverFactories);
        $this->assertSameAssociativeArray($expected[0], $servers[0]->toArray());
    }

    /**
     * Assert equality as an associative array.
     */
    protected function assertSameAssociativeArray(array $expected, array $actual): void
    {
        foreach ($expected as $key => $value) {
            if (is_array($value)) {
                $this->assertSameAssociativeArray($value, $actual[$key]);
                unset($actual[$key]);
                continue;
            }
            self::assertSame($value, $actual[$key]);
            unset($actual[$key]);
        }
        self::assertCount(0, $actual, sprintf('[%s] does not matched keys.', join(', ', array_keys($actual))));
    }

    public static function serverFQCNProvider(): array
    {
        return [
            'Can build server without variables' => [
                [ServerWithoutVariables::class],
                [
                    [
                        'url' => 'http://example.com',
                        'description' => 'sample_description',
                    ],
                ],
            ],
            'Can build server with variables' => [
                [ServerWithVariables::class],
                [
                    [
                        'url' => 'http://example.com',
                        'description' => 'sample_description',
                        'variables' => [
                            'variable_name' => [
                                'default' => 'variable_defalut',
                                'description' => 'variable_description',
                            ],
                        ],
                    ],
                ],
            ],
            'Can build server containing enum' => [
                [ServerWithEnum::class],
                [
                    [
                        'url' => 'http://example.com',
                        'description' => 'sample_description',
                        'variables' => [
                            'variable_name' => [
                                'default' => 'variable_defalut',
                                'description' => 'variable_description',
                                'enum' => [
                                    'A',
                                    'B',
                                    'C',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'Can build server containing variables fields in multiple formats' => [
                [ServerWithMultipleVariableFormatting::class],
                [
                    [
                        'url' => 'http://example.com',
                        'description' => 'sample_description',
                        'variables' => [
                            'variable_name' => [
                                'default' => 'variable_defalut',
                                'description' => 'variable_description',
                                'enum' => ['A', 'B'],
                            ],
                            'variable_name_B' => [
                                'default' => 'sample',
                                'description' => 'sample',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function multiTagProvider(): array
    {
        return [
            'Can build multiple server from an array of FQCNs' => [
                [ServerWithVariables::class, ServerWithMultipleVariableFormatting::class],
                [
                    [
                        'url' => 'http://example.com',
                        'description' => 'sample_description',
                        'variables' => [
                            'variable_name' => [
                                'default' => 'variable_defalut',
                                'description' => 'variable_description',
                            ],
                        ],
                    ],
                    [
                        'url' => 'http://example.com',
                        'description' => 'sample_description',
                        'variables' => [
                            'variable_name' => [
                                'enum' => ['A', 'B'],
                                'default' => 'variable_defalut',
                                'description' => 'variable_description',
                            ],
                            'variable_name_B' => [
                                'default' => 'sample',
                                'description' => 'sample',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('multiTagProvider')]
    public function testCanBuildFromServerArray(array $tagFactories, array $expected): void
    {
        $builder = app(ServerBuilder::class);
        $servers = $builder->build($tagFactories);

        $this->assertSame($expected, collect($servers)->map(static fn (Server $server): array => $server->toArray())->toArray());
    }
}

class ServerWithoutVariables extends ServerFactory
{
    public function build(): Server
    {
        return Server::create()
            ->url('http://example.com')
            ->description('sample_description');
    }
}

class ServerWithVariables extends ServerFactory
{
    public function build(): Server
    {
        return Server::create()
            ->url('http://example.com')
            ->description('sample_description')
            ->variables(
                ServerVariable::create('variable_name')
                    ->default('variable_defalut')
                    ->description('variable_description')
            );
    }
}

class ServerWithEnum extends ServerFactory
{
    public function build(): Server
    {
        return Server::create()
            ->url('http://example.com')
            ->description('sample_description')
            ->variables(
                ServerVariable::create('variable_name')
                    ->default('variable_defalut')
                    ->description('variable_description')
                    ->enum('A', 'B', 'C')
            );
    }
}

class ServerWithMultipleVariableFormatting extends ServerFactory
{
    public function build(): Server
    {
        return Server::create()
            ->url('http://example.com')
            ->description('sample_description')
            ->variables(
                ServerVariable::create('variable_name')
                    ->default('variable_defalut')
                    ->description('variable_description')
                    ->enum('A', 'B'),
                ServerVariable::create('variable_name_B')
                    ->default('sample')
                    ->description('sample')
            );
    }
}
