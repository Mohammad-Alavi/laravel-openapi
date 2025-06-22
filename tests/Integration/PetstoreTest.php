<?php

namespace Tests\Integration;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Generator;
use Tests\Doubles\Stubs\Petstore\PetController;
use Tests\Doubles\Stubs\Petstore\Security\SecuritySchemes\TestApiKeySecuritySchemeFactory;
use Tests\Doubles\Stubs\Petstore\Security\SecuritySchemes\TestHTTPBearerSecuritySchemeFactory;
use Tests\Doubles\Stubs\Servers\ServerWithMultipleVariableFormatting;
use Tests\Doubles\Stubs\Servers\ServerWithoutVariables;
use Tests\Doubles\Stubs\Servers\ServerWithVariables;

describe('PetStore', function (): void {
    it(' can be generated', function (array $servers, string $path, string $method, array $expectation): void {
        Config::set('openapi.locations', [
            'schemas' => [
                __DIR__ . '/../Doubles/Stubs/Petstore/Reusable/Schema',
            ],
            'responses' => [
                __DIR__ . '/../Doubles/Stubs/Petstore/Reusable/Response',
            ],
        ]);

        putenv('APP_URL=https://petstore.swagger.io/v1');
        Route::get('/pets', [PetController::class, 'index']);
        Route::post('/multiPetTag', [PetController::class, 'multiPetTag']);
        Route::delete('/nestedSecurityFirstTest', [PetController::class, 'nestedSecurityFirst']);
        Route::put('/nestedSecuritySecondTest', [PetController::class, 'nestedSecuritySecond']);

        Config::set('openapi.collections.default.servers', $servers['classes']);
        $spec = app(Generator::class)->generate()->asArray();

        expect(\Safe\json_encode($spec['servers']))->toBe(\Safe\json_encode($servers['expectation']))
            ->and($spec['paths'])->toHaveKey($path)
            ->and($spec['paths'][$path])->toHaveKey($method)
            ->and(\Safe\json_encode($spec['paths'][$path][$method]))->toBe(\Safe\json_encode($expectation))
            ->and($spec)->toHaveKey('components')
            ->and($spec['components'])->toHaveKey('schemas')
            ->and($spec['components']['schemas'])->toHaveKey('PetSchema')
            ->and(\Safe\json_encode($spec['components']['schemas']['PetSchema']))->toBe(
                \Safe\json_encode(
                    [
                        'type' => 'object',
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                                'format' => 'int64',
                            ],
                            'name' => [
                                'type' => 'string',
                            ],
                            'tag' => [
                                'type' => 'string',
                            ],
                        ],
                        'required' => [
                            'id',
                            'name',
                        ],
                    ],
                ),
            )->and($spec['components'])->toHaveKey('responses')
            ->and($spec['components']['responses'])->toHaveKey('ValidationErrorResponse')
            ->and($spec['components']['responses']['ValidationErrorResponse'])->toBe([
                'description' => 'Unprocessable Entity',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'message' => [
                                    'type' => 'string',
                                    'examples' => ['The given data was invalid.'],
                                ],
                                'errors' => [
                                    'type' => 'object',
                                    'additionalProperties' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'string',
                                        ],
                                    ],
                                    'examples' => [
                                        [
                                            'field' => [
                                                'Something is wrong with this field!',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]);
    })->with([
        [
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
                        'schema' => [
                            'type' => 'integer',
                            'format' => 'int32',
                        ],
                    ],
                ],
                'responses' => [
                    422 => [
                        '$ref' => '#/components/responses/ValidationErrorResponse',
                    ],
                ],
                'deprecated' => true,
            ],
        ],
        [
            'servers' => [
                'classes' => [ServerWithVariables::class],
                'expectation' => [
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
                        'schema' => [
                            'type' => 'integer',
                            'format' => 'int32',
                        ],
                    ],
                ],
                'responses' => [
                    422 => [
                        '$ref' => '#/components/responses/ValidationErrorResponse',
                    ],
                    200 => [
                        'description' => 'Resource created',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/PetSchema',
                                ],
                            ],
                        ],
                    ],
                    403 => [
                        'description' => 'Forbidden',
                    ],
                ],
                'security' => [
                    [
                        TestHTTPBearerSecuritySchemeFactory::name() => [],
                    ],
                ],
            ],
        ],
        [
            'servers' => [
                'classes' => [ServerWithMultipleVariableFormatting::class],
                'expectation' => [
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
                        'schema' => [
                            'type' => 'integer',
                            'format' => 'int32',
                        ],
                    ],
                ],
                'responses' => [
                    422 => [
                        'description' => 'Unprocessable Entity',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'message' => [
                                            'type' => 'string',
                                            'examples' => ['The given data was invalid.'],
                                        ],
                                        'errors' => [
                                            'type' => 'object',
                                            'additionalProperties' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'string',
                                                ],
                                            ],
                                            'examples' => [
                                                [
                                                    'field' => [
                                                        'Something is wrong with this field!',
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'security' => [
                    [
                        TestHTTPBearerSecuritySchemeFactory::name() => [],
                    ],
                    [
                        TestApiKeySecuritySchemeFactory::name() => [],
                    ],
                ],
            ],
        ],
        [
            'servers' => [
                'classes' => [ServerWithVariables::class, ServerWithMultipleVariableFormatting::class],
                'expectation' => [
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
                        TestHTTPBearerSecuritySchemeFactory::name() => [],
                    ],
                    [
                        TestHTTPBearerSecuritySchemeFactory::name() => [],
                        'OAuth2Password' => [
                            'order:shipping:address',
                            'order:shipping:status',
                        ],
                    ],
                ],
            ],
        ],
    ]);
})->coversNothing();
