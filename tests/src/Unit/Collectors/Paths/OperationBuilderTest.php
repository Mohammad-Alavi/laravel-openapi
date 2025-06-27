<?php

use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Attributes\Callback;
use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\Extension;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation as OperationAttribute;
use MohammadAlavi\LaravelOpenApi\Attributes\Parameters;
use MohammadAlavi\LaravelOpenApi\Attributes\RequestBody;
use MohammadAlavi\LaravelOpenApi\Attributes\Responses;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\OperationBuilder;
use MohammadAlavi\LaravelOpenApi\Objects\RouteInfo;
use Tests\src\Support\Doubles\Stubs\Attributes\TestCallbackFactory;
use Tests\src\Support\Doubles\Stubs\Attributes\TestExtensionFactory;
use Tests\src\Support\Doubles\Stubs\Attributes\TestParameterFactory;
use Tests\src\Support\Doubles\Stubs\Attributes\TestRequestBodyFactory;
use Tests\src\Support\Doubles\Stubs\Attributes\TestResponsesFactory;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\SecuritySchemes\TestBearerSecuritySchemeFactory;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\TestSingleHTTPBearerSchemeSecurityFactory;
use Tests\src\Support\Doubles\Stubs\Servers\ServerWithMultipleVariableFormatting;
use Tests\src\Support\Doubles\Stubs\Tags\TagWithExternalObjectDoc;
use Tests\src\Support\Doubles\Stubs\Tags\TagWithoutExternalDoc;

describe(class_basename(OperationBuilder::class), function (): void {
    it('can be created with many combinations', function (RouteInfo $routeInfo, array $expected): void {
        $operationBuilder = app(OperationBuilder::class);

        $operation = $operationBuilder->build($routeInfo);

        expect($operation)->key()->toBe($routeInfo->method())
            ->value()->asArray()->toBe($expected);
    })->with(
        [
            function (): array {
                $routeInfo = RouteInfo::create(
                    Route::get('test', static fn (): string => 'test'),
                );
                $routeInfo->actionAttributes = collect([
                    new OperationAttribute(
                        operationId: 'test',
                        tags: [],
                        security: null,
                        servers: [],
                        summary: '',
                        description: '',
                        deprecated: false,
                    ),
                ]);

                return [
                    'routeInfo' => $routeInfo,
                    'expected' => [
                        'summary' => '',
                        'description' => '',
                        'operationId' => 'test',
                    ],
                ];
            },
            function (): array {
                $routeInfo = RouteInfo::create(
                    Route::post('test', static fn (): string => 'test'),
                );
                $routeInfo->actionAttributes = collect([
                    new OperationAttribute(
                        operationId: 'test',
                        tags: [TagWithoutExternalDoc::class],
                        security: null,
                        servers: [],
                        summary: 'summary',
                        description: 'description',
                        deprecated: true,
                    ),
                ]);

                return [
                    'routeInfo' => $routeInfo,
                    'expected' => [
                        'tags' => ['PostWithoutExternalDoc'],
                        'summary' => 'summary',
                        'description' => 'description',
                        'operationId' => 'test',
                        'deprecated' => true,
                    ],
                ];
            },
            function (): array {
                $routeInfo = RouteInfo::create(
                    Route::delete('test', static fn (): string => 'test'),
                );
                $routeInfo->actionAttributes = collect([
                    new Callback(TestCallbackFactory::class),
                    new Collection('test'),
                    new Extension(TestExtensionFactory::class),
                    new OperationAttribute(
                        operationId: 'test',
                        tags: [TagWithExternalObjectDoc::class],
                        security: TestSingleHTTPBearerSchemeSecurityFactory::class,
                        servers: [ServerWithMultipleVariableFormatting::class],
                        summary: 'summary',
                        description: 'description',
                        deprecated: true,
                    ),
                    new Parameters(TestParameterFactory::class),
                    new RequestBody(TestRequestBodyFactory::class),
                    new Responses(TestResponsesFactory::class),
                ]);

                return [
                    'routeInfo' => $routeInfo,
                    'expected' => [
                        'tags' => ['PostWithExternalObjectDoc'],
                        'summary' => 'summary',
                        'description' => 'description',
                        'operationId' => 'test',
                        'parameters' => [
                            [
                                'name' => 'param_a',
                                'in' => 'header',
                                'schema' => [
                                    'type' => 'string',
                                ],
                            ],
                            [
                                'name' => 'param_b',
                                'in' => 'path',
                                'schema' => [
                                    'type' => 'string',
                                ],
                            ],
                            [
                                '$ref' => '#/components/parameters/TestParameter',
                            ],
                            [
                                'name' => 'param_c',
                                'in' => 'cookie',
                                'schema' => [
                                    'type' => 'string',
                                ],
                            ],
                        ],
                        'requestBody' => [],
                        'responses' => [
                            200 => [
                                'description' => 'OK',
                            ],
                        ],
                        'deprecated' => true,
                        'security' => [
                            [
                                TestBearerSecuritySchemeFactory::name() => [],
                            ],
                        ],
                        /*
                         * TODO: docs: it seems SecurityScheme object id is mandatory and if we dont set it,
                         *  it will be null in the SecurityRequirement object $securityScheme field
                         *  Based on OAS spec security requirement cant not have a name
                         */
                        'servers' => [
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
                        'callbacks' => [
                            'TestCallbackFactory' => [
                                'https://example.com/' => [],
                            ],
                        ],
                        'x-key' => 'value',
                    ],
                ];
            },
        ],
    );
})->covers(OperationBuilder::class);
