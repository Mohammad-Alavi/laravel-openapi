<?php

namespace Tests\Integration;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Doubles\Fakes\Petstore\PetController;
use Tests\Doubles\Stubs\Servers\ServerWithMultipleVariableFormatting;
use Tests\Doubles\Stubs\Servers\ServerWithoutVariables;
use Tests\Doubles\Stubs\Servers\ServerWithVariables;
use Tests\IntegrationTestCase;

/** @see https://github.com/OAI/OpenAPI-Specification/blob/master/examples/v3.0/petstore.yaml */
#[CoversClass(PetController::class)]
class PetstoreTest extends IntegrationTestCase
{
    public static function expectationProvider(): \Iterator
    {
        yield [
            'servers' => [
                'classes' => [ServerWithoutVariables::class],
                'expectation' => [
                    [
                        'url' => 'https://example.com',
                        'description' => 'sample_description',
                    ],
                ],
            ],
            'path' => '/pets',
            'method' => 'get',
            'expectation' => [
                'tags' => [
                    'Pet',
                ],
                'summary' => 'List all pets.',
                'description' => 'List all pets from the database.',
                'operationId' => 'listPets',
                'parameters' => [
                    [
                        'name' => 'limit',
                        'in' => 'query',
                        'description' => 'How many items to return at one time (max 100)',
                        'required' => false,
                        'schema' => [
                            'format' => 'int32',
                            'type' => 'integer',
                        ],
                    ],
                ],
                'responses' => [
                    422 => [
                        '$ref' => '#/components/responses/ErrorValidation',
                    ],
                ],
                'deprecated' => true,
            ],
        ];
        yield [
            'servers' => [
                'classes' => [ServerWithVariables::class],
                'expectation' => [
                    [
                        'url' => 'https://example.com',
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
            'path' => '/multiPetTag',
            'method' => 'post',
            'expectation' => [
                'tags' => [
                    'Pet',
                    'AnotherPet',
                ],
                'summary' => 'List all pets.',
                'description' => 'List all pets from the database.',
                'operationId' => 'multiPetTag',
                'parameters' => [
                    [
                        'name' => 'limit',
                        'in' => 'query',
                        'description' => 'How many items to return at one time (max 100)',
                        'required' => false,
                        'schema' => [
                            'format' => 'int32',
                            'type' => 'integer',
                        ],
                    ],
                ],
                'responses' => [
                    422 => [
                        '$ref' => '#/components/responses/ErrorValidation',
                    ],
                ],
                'deprecated' => false,
                'security' => [
                    [
                        'BearerToken' => [],
                    ],
                ],
            ],
        ];
        yield [
            'servers' => [
                'classes' => [ServerWithMultipleVariableFormatting::class],
                'expectation' => [
                    [
                        'url' => 'https://example.com',
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
            'path' => '/nestedSecurityFirstTest',
            'method' => 'delete',
            'expectation' => [
                'tags' => [
                    'Pet',
                ],
                'summary' => 'List all pets.',
                'description' => 'List all pets from the database.',
                'operationId' => 'nestedSecurityFirstTest',
                'parameters' => [
                    [
                        'name' => 'limit',
                        'in' => 'query',
                        'description' => 'How many items to return at one time (max 100)',
                        'required' => false,
                        'schema' => [
                            'format' => 'int32',
                            'type' => 'integer',
                        ],
                    ],
                ],
                'security' => [
                    [
                        'OAuth2PasswordGrant' => [],
                    ],
                    [
                        'BearerToken' => [],
                    ],
                ],
            ],
        ];
        yield [
            'servers' => [
                'classes' => [ServerWithVariables::class, ServerWithMultipleVariableFormatting::class],
                'expectation' => [
                    [
                        'url' => 'https://example.com',
                        'description' => 'sample_description',
                        'variables' => [
                            'variable_name' => [
                                'default' => 'variable_defalut',
                                'description' => 'variable_description',
                            ],
                        ],
                    ],
                    [
                        'url' => 'https://example.com',
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
            'path' => '/nestedSecuritySecondTest',
            'method' => 'put',
            'expectation' => [
                'tags' => [
                    'AnotherPet',
                ],
                'summary' => 'List all pets.',
                'description' => 'List all pets from the database.',
                'operationId' => 'nestedSecuritySecondTest',
                'security' => [
                    [
                        'BearerToken' => [],
                    ],
                    [
                        'OAuth2PasswordGrant' => [],
                        'BearerToken' => [],
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('expectationProvider')]
    public function testGenerate(array $servers, string $path, string $method, array $expectation): void
    {
        Config::set('openapi.collections.default.servers', $servers['classes']);
        $spec = $this->generate()->jsonSerialize();

        $this->assertSame($servers['expectation'], $spec['servers']);

        $this->assertArrayHasKey($path, $spec['paths']);
        $this->assertArrayHasKey($method, $spec['paths'][$path]);

        $this->assertSame($expectation, $spec['paths'][$path][$method]);

        $this->assertArrayHasKey('components', $spec);
        $this->assertArrayHasKey('schemas', $spec['components']);
        $this->assertArrayHasKey('Pet', $spec['components']['schemas']);

        $this->assertSame([
            'type' => 'object',
            'required' => [
                'id',
                'name',
            ],
            'properties' => [
                'id' => [
                    'format' => 'int64',
                    'type' => 'integer',
                ],
                'name' => [
                    'type' => 'string',
                ],
                'tag' => [
                    'type' => 'string',
                ],
            ],
        ], $spec['components']['schemas']['Pet']);
    }

    protected function setUp(): void
    {
        putenv('APP_URL=https://petstore.swagger.io/v1');

        parent::setUp();

        Route::get('/pets', [PetController::class, 'index']);
        Route::post('/multiPetTag', [PetController::class, 'multiPetTag']);
        Route::delete('/nestedSecurityFirstTest', [PetController::class, 'nestedSecurityFirst']);
        Route::put('/nestedSecuritySecondTest', [PetController::class, 'nestedSecuritySecond']);
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('openapi.locations.schemas', [
            __DIR__ . '/../Doubles/Fakes/Petstore/Schemas',
        ]);
    }
}
