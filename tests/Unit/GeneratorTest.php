<?php

use Illuminate\Support\Facades\Config;
use MohammadAlavi\LaravelOpenApi\Generator;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ServerFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\TagFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\Fields\JsonSchemaDialect;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\URL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;
use Tests\Doubles\Stubs\Petstore\Security\ExampleNoSecurityRequirementSecurity;
use Tests\Doubles\Stubs\Petstore\Security\SecuritySchemes\TestHTTPBearerSecuritySchemeFactory;
use Tests\Doubles\Stubs\Petstore\Security\TestComplexMultiSecurityFactory;

beforeEach(function (): void {
    Config::set('openapi', [
        'collections' => [
            'default' => [
                'info' => [
                    'title' => 'Test Default API',
                    'description' => 'Test Default API description',
                    'version' => '1.0.0',
                    'contact' => [
                        'name' => 'Mohammad Alavi',
                    ],
                ],
                'servers' => [
                    (new class extends ServerFactory {
                        public function build(): Server
                        {
                            return Server::create(URL::create('https://example.com'));
                        }
                    })::class,
                ],
                'tags' => [
                    (new class extends TagFactory {
                        public function build(): Tag
                        {
                            return Tag::create(Name::create('test'));
                        }
                    })::class,
                ],
                'security' => ExampleNoSecurityRequirementSecurity::class,
            ],
            'test' => [
                'info' => [
                    'title' => 'Test API',
                    'description' => 'Test API description',
                    'version' => '2.0.0',
                    'contact' => [
                        'name' => 'Mohammad Alavi the second',
                    ],
                ],
                'servers' => [
                    (new class extends ServerFactory {
                        public function build(): Server
                        {
                            return Server::create(URL::create('https://test.com'));
                        }
                    })::class,
                    (new class extends ServerFactory {
                        public function build(): Server
                        {
                            return Server::create(URL::create('https://local.com'));
                        }
                    })::class,
                ],
                'tags' => [
                    (new class extends TagFactory {
                        public function build(): Tag
                        {
                            return Tag::create(Name::create('test'));
                        }
                    })::class,
                ],
                'security' => TestComplexMultiSecurityFactory::class,
                'extensions' => [
                    'x-tagGroups' => [
                        [
                            'name' => 'General',
                            'tags' => [
                                'user',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'locations' => [
            'callbacks' => [
                __DIR__ . '/../Doubles/Stubs/Collectors/Components/Callback',
            ],
            'request_bodies' => [
                __DIR__ . '/../Doubles/Stubs/Collectors/Components/RequestBody',
            ],
            'responses' => [
                __DIR__ . '/../Doubles/Stubs/Collectors/Components/Response',
            ],
            'schemas' => [
                __DIR__ . '/../Doubles/Stubs/Collectors/Components/Schema',
            ],
            'security' => [
                __DIR__ . '/../Doubles/Stubs/Collectors/Components/SecurityScheme',
            ],
        ],
    ]);
});

describe('Generator', function (): void {
    it('should generate OpenApi object', function (string $collection, array $expectation): void {
        $generator = app(Generator::class);

        $openApi = $generator->generate($collection);

        expect($openApi->asArray())->toEqual($expectation);
    })->with([
        'test collection' => [
            'collection' => 'test',
            'expectation' => [
                'openapi' => '3.1.1',
                'info' => [
                    'title' => 'Test API',
                    'description' => 'Test API description',
                    'contact' => [
                        'name' => 'Mohammad Alavi the second',
                    ],
                    'version' => '2.0.0',
                ],
                'servers' => [
                    [
                        'url' => 'https://test.com',
                    ],
                    [
                        'url' => 'https://local.com',
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
                            '/explicit-collection-callback' => [],
                        ],
                        'MultiCollectionCallback' => [
                            '/multi-collection-callback' => [],
                        ],
                    ],
                ],
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
                'tags' => [
                    [
                        'name' => 'test',
                    ],
                ],
                'x-tagGroups' => [
                    [
                        'name' => 'General',
                        'tags' => [
                            'user',
                        ],
                    ],
                ],
                'paths' => [],
                'jsonSchemaDialect' => JsonSchemaDialect::v31x()->value(),
            ],
        ],
        'default collection' => [
            'collection' => Generator::COLLECTION_DEFAULT,
            'expectation' => [
                'openapi' => '3.1.1',
                'info' => [
                    'title' => 'Test Default API',
                    'description' => 'Test Default API description',
                    'contact' => [
                        'name' => 'Mohammad Alavi',
                    ],
                    'version' => '1.0.0',
                ],
                'servers' => [
                    [
                        'url' => 'https://example.com',
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
                            '/multi-collection-callback' => [],
                        ],
                        'ImplicitDefaultCallback' => [
                            '/implicit-default-callback' => [],
                        ],
                    ],
                ],
                'security' => [
                    [],
                ],
                'tags' => [
                    [
                        'name' => 'test',
                    ],
                ],
                'paths' => [],
                'jsonSchemaDialect' => JsonSchemaDialect::v31x()->value(),
            ],
        ],
    ]);
})->covers(Generator::class);
