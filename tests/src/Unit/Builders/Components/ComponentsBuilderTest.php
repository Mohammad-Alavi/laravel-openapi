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
                'examples' => [
                    __DIR__ . '/../../../Support/Doubles/Stubs/Builders/Components/Example',
                ],
                'request_bodies' => [
                    __DIR__ . '/../../../Support/Doubles/Stubs/Builders/Components/RequestBody',
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
                    'examples' => [
                        'MultiCollectionExample' => [
                            'value' => 'Example Value',
                        ],
                        'ExplicitCollectionExample' => [
                            'value' => 'Example Value',
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
                    'examples' => [
                        'MultiCollectionExample' => [
                            'value' => 'Example Value',
                        ],
                        'ImplicitCollectionExample' => [
                            'externalValue' => 'Example External Value',
                        ],
                    ],
                ],
            ],
        ],
    );
})->covers(ComponentsBuilder::class);
