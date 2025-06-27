<?php

use Illuminate\Support\Facades\Config;
use MohammadAlavi\LaravelOpenApi\Builders\Components\ComponentsBuilder;
use MohammadAlavi\LaravelOpenApi\Generator;
use Pest\Expectation;

describe(class_basename(ComponentsBuilder::class), function (): void {
    beforeEach(function (): void {
        Config::set('openapi', [
            //            'collections' => [
            //                'test' => [
            //                    'security' => [
            //                        (new class () extends SecuritySchemeFactory {
            //                            public function build(): SecurityScheme
            //                            {
            //                                return SecurityScheme::create('test')
            //                                    ->type('http')
            //                                    ->scheme('bearer');
            //                            }
            //                        })::class,
            //                    ],
            //                ],
            //            ],
            'locations' => [
                'callbacks' => [
                    __DIR__ . '/../../Doubles/Stubs/Collectors/Components/Callback',
                ],
                'request_bodies' => [
                    __DIR__ . '/../../Doubles/Stubs/Collectors/Components/RequestBody',
                ],
                'responses' => [
                    __DIR__ . '/../../Doubles/Stubs/Collectors/Components/Response',
                ],
                'schemas' => [
                    __DIR__ . '/../../Doubles/Stubs/Collectors/Components/Schema',
                ],
                'security' => [
                    __DIR__ . '/../../Doubles/Stubs/Collectors/Components/SecurityScheme',
                ],
            ],
        ]);
    });

    it('can collect components', function (string|null $collection, array|null $expectation): void {
        $componentsBuilder = app(ComponentsBuilder::class);

        $result = $componentsBuilder->build($collection);

        expect($result?->asArray())->unless(
            is_null($result),
            fn (Expectation $xp): Expectation => $xp->toEqual($expectation),
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
                            'https://example.com/explicit-collection-callback' => [],
                        ],
                        'MultiCollectionCallback' => [
                            'https://example.com/multi-collection-callback' => [],
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
                            'https://example.com/multi-collection-callback' => [],
                        ],
                        'ImplicitDefaultCallback' => [
                            'https://example.com/implicit-default-callback' => [],
                        ],
                    ],
                ],
            ],
        ],
    );
})->covers(ComponentsBuilder::class);
