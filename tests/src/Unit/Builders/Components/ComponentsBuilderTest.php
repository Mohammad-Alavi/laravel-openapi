<?php

use Illuminate\Support\Facades\Config;
use MohammadAlavi\LaravelOpenApi\Builders\Components\ComponentsBuilder;
use MohammadAlavi\LaravelOpenApi\Generator;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Components;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\Http;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\SecurityScheme;
use Pest\Expectation;

describe(class_basename(ComponentsBuilder::class), function (): void {
    beforeEach(function (): void {
        Config::set('openapi', [
            'collections' => [
                'test' => [
                    'security' => [
                        (new class extends SecuritySchemeFactory {
                            public function component(): SecurityScheme
                            {
                                return SecurityScheme::http(Http::bearer());
                            }
                        })::class,
                    ],
                ],
            ],
            'locations' => [
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
                'headers' => [
                    __DIR__ . '/../../../Support/Doubles/Stubs/Builders/Components/Header',
                ],
                'callbacks' => [
                    __DIR__ . '/../../../Support/Doubles/Stubs/Builders/Components/Callback',
                ],
                'security_schemes' => [
                    __DIR__ . '/../../../Support/Doubles/Stubs/Builders/Components/SecurityScheme',
                ],
            ],
        ]);
    });

    it('can collect components', function (string|null $collection, array|null $expectation): void {
        $componentsBuilder = app(ComponentsBuilder::class);

        /** @var Components|null $result */
        $result = $componentsBuilder->build($collection);

        expect($result?->unserializeToArray())->unless(
            is_null($result),
            function (Expectation $xp) use ($expectation): Expectation {
                return $xp->toEqual($expectation);
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
                        'ExplicitCollectionResponse' => [
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
                        'MultiCollectionHeader' => [],
                        'ExplicitCollectionHeader' => [],
                    ],
                    'callbacks' => [
                        'ExplicitCollectionCallback' => [
                            'https://laragen.io/explicit-collection-callback' => [],
                        ],
                        'MultiCollectionCallback' => [
                            'https://laragen.io/multi-collection-callback' => [],
                        ],
                    ],
                    'securitySchemes' => [
                        'MultiCollectionSecurityScheme' => [
                            'type' => 'http',
                            'scheme' => 'basic',
                        ],
                        'ExplicitCollectionSecurityScheme' => [
                            'type' => 'http',
                            'scheme' => 'basic',
                        ],
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
                        'MultiCollectionExample' => [
                            'value' => 'Example Value',
                        ],
                        'ImplicitCollectionExample' => [
                            'externalValue' => 'Example External Value',
                        ],
                    ],
                    'requestBodies' => [
                        'MultiCollectionRequestBody' => [],
                        'ImplicitCollectionRequestBody' => [],
                    ],
                    'headers' => [
                        'MultiCollectionHeader' => [],
                        'ImplicitCollectionHeader' => [],
                    ],
                    'callbacks' => [
                        'MultiCollectionCallback' => [
                            'https://laragen.io/multi-collection-callback' => [],
                        ],
                        'ImplicitDefaultCallback' => [
                            'https://laragen.io/implicit-default-callback' => [],
                        ],
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
                ],
            ],
        ],
    );
})->covers(ComponentsBuilder::class);
