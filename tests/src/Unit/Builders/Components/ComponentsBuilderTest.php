<?php

use Illuminate\Support\Facades\Config;
use MohammadAlavi\LaravelOpenApi\Builders\ComponentsBuilder\ComponentsBuilder;
use MohammadAlavi\LaravelOpenApi\Generator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Components;
use Pest\Expectation;

describe(class_basename(ComponentsBuilder::class), function (): void {
    beforeEach(function (): void {
        $componentPaths = [
            'headers' => [
                __DIR__ . '/../../../Support/Doubles/Stubs/Builders/Components/Header',
            ],
            'security_schemes' => [
                __DIR__ . '/../../../Support/Doubles/Stubs/Builders/Components/SecurityScheme',
            ],
            'links' => [
                __DIR__ . '/../../../Support/Doubles/Stubs/Builders/Components/Link',
            ],
            'callbacks' => [
                __DIR__ . '/../../../Support/Doubles/Stubs/Builders/Components/Callback',
            ],
            'path_items' => [
                __DIR__ . '/../../../Support/Doubles/Stubs/Builders/Components/PathItem',
            ],
            'schemas' => [
                __DIR__ . '/../../../Support/Doubles/Stubs/Builders/Components/Schema',
            ],
            'responses' => [
                __DIR__ . '/../../../Support/Doubles/Stubs/Builders/Components/Response',
            ],
            'parameters' => [
                __DIR__ . '/../../../Support/Doubles/Stubs/Builders/Components/Parameter',
            ],
            'examples' => [
                __DIR__ . '/../../../Support/Doubles/Stubs/Builders/Components/Example',
            ],
            'request_bodies' => [
                __DIR__ . '/../../../Support/Doubles/Stubs/Builders/Components/RequestBody',
            ],
        ];
        Config::set('openapi', [
            'collections' => [
                'default' => [
                    'components' => $componentPaths,
                ],
                'test' => [
                    'components' => $componentPaths,
                ],
            ],
        ]);
    });

    it('can collect components', function (string|null $collection, array|null $expectation): void {
        $componentsBuilder = app(ComponentsBuilder::class);

        /** @var Components|null $result */
        $result = $componentsBuilder->build($collection);

        when(
            is_null($expectation),
            function () use ($result): Expectation {
                return expect($result)->toBeNull();
            },
        );

        when(
            !is_null($expectation),
            function () use ($result, $expectation): Expectation {
                return expect($result->unserializeToArray())->toBe($expectation);
            },
        );
    })->with(
        [
            'none existing collection' => [
                'collection' => 'unknown',
                'expectation' => null,
            ],
            'test collection' => [
                'collection' => 'test',
                'expectation' => [
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
                    'parameters' => [
                        'MultiCollectionParameter' => [
                            'name' => 'test',
                            'in' => 'cookie',
                            'schema' => [
                                'type' => 'string',
                            ],
                        ],
                        'ExplicitCollectionParameter' => [
                            'name' => 'user_id',
                            'in' => 'path',
                            'schema' => [
                                'type' => 'string',
                            ],
                        ],
                    ],
                    'examples' => [
                        'MultiCollectionExample' => [
                            'value' => 'Example Value',
                        ],
                        'ExplicitCollectionExample' => [
                            'value' => 'Example Value',
                        ],
                    ],
                    'requestBodies' => [
                        'MultiCollectionRequestBody' => [],
                        'ExplicitCollectionRequestBody' => [],
                    ],
                    'headers' => [
                        'ExplicitCollectionHeader' => [],
                        'MultiCollectionHeader' => [],
                    ],
                    'securitySchemes' => [
                        'ExplicitCollectionSecurityScheme' => [
                            'type' => 'http',
                            'scheme' => 'basic',
                        ],
                        'MultiCollectionSecurityScheme' => [
                            'type' => 'http',
                            'scheme' => 'basic',
                        ],
                    ],
                    'links' => [
                        'MultiCollectionLink' => [],
                        'ExplicitCollectionLink' => [],
                    ],
                    'callbacks' => [
                        'ExplicitCollectionCallback' => [
                            'https://laragen.io/explicit-collection-callback' => [],
                        ],
                        'MultiCollectionCallback' => [
                            'https://laragen.io/multi-collection-callback' => [],
                        ],
                    ],
                    'pathItems' => [
                        'MultiCollectionPathItem' => [],
                        'ExplicitCollectionPathItem' => [],
                    ],
                ],
            ],
            'explicit default collection' => [
                'collection' => Generator::COLLECTION_DEFAULT,
                'expectation' => [
                    'schemas' => [
                        'ImplicitCollectionSchema' => [
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
                        'ImplicitCollectionResponse' => [
                            'description' => 'OK',
                        ],
                        'MultiCollectionResponse' => [
                            'description' => 'OK',
                        ],
                    ],
                    'parameters' => [
                        'MultiCollectionParameter' => [
                            'name' => 'test',
                            'in' => 'cookie',
                            'schema' => [
                                'type' => 'string',
                            ],
                        ],
                        'ImplicitCollectionParameter' => [
                            'name' => 'limit',
                            'in' => 'query',
                            'schema' => [
                                'type' => 'integer',
                            ],
                        ],
                    ],
                    'examples' => [
                        'ImplicitCollectionExample' => [
                            'externalValue' => 'Example External Value',
                        ],
                        'MultiCollectionExample' => [
                            'value' => 'Example Value',
                        ],
                    ],
                    'requestBodies' => [
                        'ImplicitCollectionRequestBody' => [],
                        'MultiCollectionRequestBody' => [],
                    ],
                    'headers' => [
                        'MultiCollectionHeader' => [],
                        'ImplicitCollectionHeader' => [],
                    ],
                    'securitySchemes' => [
                        'MultiCollectionSecurityScheme' => [
                            'type' => 'http',
                            'scheme' => 'basic',
                        ],
                        'ImplicitCollectionSecurityScheme' => [
                            'type' => 'http',
                            'scheme' => 'basic',
                        ],
                    ],
                    'links' => [
                        'MultiCollectionLink' => [],
                        'ImplicitCollectionLink' => [],
                    ],
                    'callbacks' => [
                        'ImplicitDefaultCallback' => [
                            'https://laragen.io/implicit-default-callback' => [],
                        ],
                        'MultiCollectionCallback' => [
                            'https://laragen.io/multi-collection-callback' => [],
                        ],
                    ],
                    'pathItems' => [
                        'MultiCollectionPathItem' => [],
                        'ImplicitCollectionPathItem' => [],
                    ],
                ],
            ],
        ],
    );
})->covers(ComponentsBuilder::class);
