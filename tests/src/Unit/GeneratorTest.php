<?php

use Illuminate\Support\Facades\Config;
use MohammadAlavi\LaravelOpenApi\Factories\ExampleFactory;
use MohammadAlavi\LaravelOpenApi\Generator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\Fields\JsonSchemaDialect;
use Workbench\App\Petstore\Security\SecuritySchemes\TestBearerSecuritySchemeFactory;
use Workbench\App\Petstore\Security\SecuritySchemes\TestOAuth2PasswordSecuritySchemeFactory;

beforeEach(function (): void {
    Config::set('openapi', [
        'collections' => [
            'default' => [
                'openapi' => ExampleFactory::class,
            ],
            'test' => [
                'openapi' => ExampleFactory::class,
            ],
        ],
        'locations' => [
            'callbacks' => [
                __DIR__ . '/../Support/Doubles/Stubs/Builders/Components/Callback',
            ],
            'request_bodies' => [
                __DIR__ . '/../Support/Doubles/Stubs/Builders/Components/RequestBody',
            ],
            'responses' => [
                __DIR__ . '/../Support/Doubles/Stubs/Builders/Components/Response',
            ],
            'schemas' => [
                __DIR__ . '/../Support/Doubles/Stubs/Builders/Components/Schema',
            ],
        ],
    ]);
});

describe('Generator', function (): void {
    it('should generate OpenApi object', function (string $collection, array $expectation): void {
        $generator = app(Generator::class);

        $openApi = $generator->generate($collection);

        expect($openApi->unserializeToArray())->toEqual($expectation);
    })->with([
        'test collection' => [
            'collection' => 'test',
            'expectation' => [
                'openapi' => '3.1.1',
                'info' => [
                    'title' => 'https://laragen.io',
                    'description' => 'This is the default OpenAPI specification for the application.',
                    'contact' => [
                        'name' => 'Example Contact',
                        'url' => 'https://example.com/',
                        'email' => 'example@example.com',
                    ],
                    'version' => '1.0.0',
                    'summary' => 'Default OpenAPI Specification',
                    'license' => [
                        'name' => 'MIT',
                        'url' => 'https://github.com/',
                    ],
                ],
                'servers' => [
                    [
                        'url' => 'https://laragen.io',
                    ],
                ],
                'components' => [
                    'schemas' => [
                        'ExplicitCollectionSchema' => [
                            'type' => 'object',
                            'properties' => [
                                'id' => [
                                    'type' => 'integer',
                                ],
                            ],
                        ],
                        'MultiCollectionSchema' => [
                            'type' => 'object',
                            'properties' => [
                                'id' => [
                                    'type' => 'integer',
                                ],
                            ],
                        ],
                    ],
                    'responses' => [
                        'MultiCollectionResponse' => [
                            'description' => 'OK',
                        ],
                        'ExplicitCollectionResponse' => [
                            'description' => 'OK',
                        ],
                    ],
                    'requestBodies' => [
                        'MultiCollectionRequestBody' => [],
                        'ExplicitCollectionRequestBody' => [],
                    ],
                    'callbacks' => [
                        'ExplicitCollectionCallback' => [
                            'https://laragen.io/explicit-collection-callback' => [],
                        ],
                        'MultiCollectionCallback' => [
                            'https://laragen.io/multi-collection-callback' => [],
                        ],
                    ],
                ],
                'security' => [
                    [
                        TestBearerSecuritySchemeFactory::name() => [],
                    ],
                    [
                        TestBearerSecuritySchemeFactory::name() => [],
                        TestOAuth2PasswordSecuritySchemeFactory::name() => [
                            'order:shipping:address',
                            'order:shipping:status',
                        ],
                    ],
                ],
                'tags' => [
                    [
                        'name' => 'test',
                        'description' => 'This is a test tag.',
                    ],
                ],
                'paths' => [],
                'jsonSchemaDialect' => JsonSchemaDialect::v31x()->value(),
                'x-example' => [
                    'name' => 'General',
                    'tags' => [
                        'user',
                    ],
                ],
            ],
        ],
        'default collection' => [
            'collection' => Generator::COLLECTION_DEFAULT,
            'expectation' => [
                'openapi' => '3.1.1',
                'info' => [
                    'title' => 'https://laragen.io',
                    'description' => 'This is the default OpenAPI specification for the application.',
                    'contact' => [
                        'name' => 'Example Contact',
                        'url' => 'https://example.com/',
                        'email' => 'example@example.com',
                    ],
                    'version' => '1.0.0',
                    'summary' => 'Default OpenAPI Specification',
                    'license' => [
                        'name' => 'MIT',
                        'url' => 'https://github.com/',
                    ],
                ],
                'servers' => [
                    [
                        'url' => 'https://laragen.io',
                    ],
                ],
                'components' => [
                    'schemas' => [
                        'MultiCollectionSchema' => [
                            'type' => 'object',
                            'properties' => [
                                'id' => [
                                    'type' => 'integer',
                                ],
                            ],
                        ],
                        'ImplicitCollectionSchema' => [
                            'type' => 'object',
                            'properties' => [
                                'id' => [
                                    'type' => 'integer',
                                ],
                            ],
                        ],
                    ],
                    'responses' => [
                        'MultiCollectionResponse' => [
                            'description' => 'OK',
                        ],
                        'ImplicitCollectionResponse' => [
                            'description' => 'OK',
                        ],
                    ],
                    'requestBodies' => [
                        'MultiCollectionRequestBody' => [],
                        'ImplicitCollectionRequestBody' => [],
                    ],
                    'callbacks' => [
                        'MultiCollectionCallback' => [
                            'https://laragen.io/multi-collection-callback' => [],
                        ],
                        'ImplicitDefaultCallback' => [
                            'https://laragen.io/implicit-default-callback' => [],
                        ],
                    ],
                ],
                'security' => [
                    [
                        TestBearerSecuritySchemeFactory::name() => [],
                    ],
                    [
                        TestBearerSecuritySchemeFactory::name() => [],
                        TestOAuth2PasswordSecuritySchemeFactory::name() => [
                            'order:shipping:address',
                            'order:shipping:status',
                        ],
                    ],
                ],
                'tags' => [
                    [
                        'name' => 'test',
                        'description' => 'This is a test tag.',
                    ],
                ],
                'paths' => [],
                'jsonSchemaDialect' => JsonSchemaDialect::v31x()->value(),
                'x-example' => [
                    'name' => 'General',
                    'tags' => [
                        'user',
                    ],
                ],
            ],
        ],
    ]);
})->covers(Generator::class);
