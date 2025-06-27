<?php

namespace Tests\src\Unit\Builders;

use MohammadAlavi\LaravelOpenApi\Builders\ServerBuilder;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\src\Support\Doubles\Stubs\Servers\ServerWithEnum;
use Tests\src\Support\Doubles\Stubs\Servers\ServerWithMultipleVariableFormatting;
use Tests\src\Support\Doubles\Stubs\Servers\ServerWithoutVariables;
use Tests\src\Support\Doubles\Stubs\Servers\ServerWithVariables;
use Tests\TestCase;

#[CoversClass(ServerBuilder::class)]
class ServerBuilderTest extends TestCase
{
    public static function serverFQCNProvider(): \Iterator
    {
        yield 'Can build server without variables' => [
            [ServerWithoutVariables::class],
            [
                [
                    'url' => 'https://example.com',
                    'description' => 'sample_description',
                ],
            ],
        ];
        yield 'Can build server with variables' => [
            [ServerWithVariables::class],
            [
                [
                    'url' => 'https://example.com',
                    'description' => 'sample_description',
                    'variables' => [
                        'variable_name' => [
                            'default' => 'variable_default',
                            'description' => 'variable_description',
                        ],
                    ],
                ],
            ],
        ];
        yield 'Can build server containing enum' => [
            [ServerWithEnum::class],
            [
                [
                    'url' => 'https://example.com',
                    'description' => 'sample_description',
                    'variables' => [
                        'variable_name' => [
                            'default' => 'B',
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
        ];
        yield 'Can build server containing variables fields in multiple formats' => [
            [ServerWithMultipleVariableFormatting::class],
            [
                [
                    'url' => 'https://example.com',
                    'description' => 'sample_description',
                    'variables' => [
                        'ServerVariableA' => [
                            'default' => 'B',
                            'description' => 'variable_description',
                            'enum' => ['A', 'B'],
                        ],
                        'ServerVariableB' => [
                            'default' => 'sample',
                            'description' => 'sample_description',
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function multiTagProvider(): \Iterator
    {
        yield 'Can build multiple server from an array of FQCNs' => [
            [ServerWithVariables::class, ServerWithMultipleVariableFormatting::class],
            [
                [
                    'url' => 'https://example.com',
                    'description' => 'sample_description',
                    'variables' => [
                        'variable_name' => [
                            'default' => 'variable_default',
                            'description' => 'variable_description',
                        ],
                    ],
                ],
                [
                    'url' => 'https://example.com',
                    'description' => 'sample_description',
                    'variables' => [
                        'ServerVariableA' => [
                            'enum' => ['A', 'B'],
                            'default' => 'B',
                            'description' => 'variable_description',
                        ],
                        'ServerVariableB' => [
                            'default' => 'sample',
                            'description' => 'sample_description',
                        ],
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('serverFQCNProvider')]
    public function testCanBuildServerFromFQCN(array $factories, array $expected): void
    {
        $serverBuilder = new ServerBuilder();
        $servers = $serverBuilder->build($factories);
        $this->assertSameAssociativeArray($expected[0], $servers[0]->asArray());
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

            $this->assertSame($value, $actual[$key]);
            unset($actual[$key]);
        }

        $this->assertCount(
            0,
            $actual,
            sprintf('[%s] does not matched keys.', implode(', ', array_keys($actual))),
        );
    }

    #[DataProvider('multiTagProvider')]
    public function testCanBuildFromServerArray(array $factories, array $expected): void
    {
        $serverBuilder = app(ServerBuilder::class);
        $servers = $serverBuilder->build($factories);

        $this->assertSame(
            $expected,
            collect($servers)
            ->map(
                static fn (Server $server): array => $server->asArray(),
            )->toArray(),
        );
    }
}
