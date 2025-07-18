<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Factories\OpenAPIFactory;
use MohammadAlavi\LaravelOpenApi\Generator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\OpenAPI;
use Tests\src\Support\Doubles\Stubs\Objects\MultiActionController;

describe(class_basename(Generator::class), function (): void {
    it('should generate OpenApi object', function (string $collection, array $expectation): void {
        Route::get('/test', [MultiActionController::class, 'anotherExample']);
        $factory = Factory::class;

        Config::set('openapi', [
            'collections' => [
                'default' => [
                    'openapi' => $factory,
                    'components' => [
                        'callbacks' => [
                            __DIR__ . '/../Support/Doubles/Stubs/Builders/Components/Callback',
                        ],
                    ],
                ],
                'example' => [
                    'openapi' => $factory,
                    'components' => [
                        'responses' => [
                            __DIR__ . '/../Support/Doubles/Stubs/Builders/Components/Response',
                        ],
                    ],
                ],
                'test' => [
                    'openapi' => $factory,
                    'components' => [
                        'schemas' => [
                            __DIR__ . '/../Support/Doubles/Stubs/Builders/Components/Schema',
                        ],
                        'request_bodies' => [
                            __DIR__ . '/../Support/Doubles/Stubs/Builders/Components/RequestBody',
                        ],
                    ],
                ],
            ],
        ]);
        $openApi = app(Generator::class)->generate($collection);

        $result = $openApi->unserializeToArray();

        expect($result['components'])->toEqual($expectation['components'])
            ->and($result['paths'])->toEqual($expectation['paths']);
    })->with([
        'default collection' => [
            'collection' => Generator::COLLECTION_DEFAULT,
            'expectation' => [
                'paths' => [],
                'components' => [
                    'callbacks' => [
                        'MultiCollectionCallback' => [
                            'https://laragen.io/multi-collection-callback' => [],
                        ],
                        'ImplicitDefaultCallback' => [
                            'https://laragen.io/implicit-default-callback' => [],
                        ],
                    ],
                ],
            ],
        ],
        'example collection' => [
            'collection' => 'example',
            'expectation' => [
                'paths' => [
                    '/test' => [
                        'get' => [
                            'responses' => [
                                '422' => [
                                    '$ref' => '#/components/responses/ValidationErrorResponse',
                                ],
                            ],
                        ],
                    ],
                ],
                'components' => [
                    'responses' => [
                        'ValidationErrorResponse' => [
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
                        'MultiCollectionResponse' => [
                            'description' => 'OK',
                        ],
                    ],
                ],
            ],
        ],
        'test collection' => [
            'collection' => 'test',
            'expectation' => [
                'paths' => [],
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
                    'requestBodies' => [
                        'MultiCollectionRequestBody' => [],
                        'ExplicitCollectionRequestBody' => [],
                    ],
                ],
            ],
        ],
    ]);
})->covers(Generator::class);

final readonly class Factory extends OpenAPIFactory
{
    public function instance(): OpenAPI
    {
        return OpenAPI::v311(
            Info::create(
                'https://laragen.io',
                '1.0.0',
            ),
        );
    }
}
