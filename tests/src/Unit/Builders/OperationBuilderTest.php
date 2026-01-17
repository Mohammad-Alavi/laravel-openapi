<?php

use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\Extension;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation as OperationAttribute;
use MohammadAlavi\LaravelOpenApi\Builders\OperationBuilder;
use MohammadAlavi\LaravelOpenApi\Support\RouteInfo;
use Tests\src\Support\Doubles\Stubs\Attributes\TestCallbackFactory;
use Tests\src\Support\Doubles\Stubs\Attributes\TestExtensionFactory;
use Tests\src\Support\Doubles\Stubs\Attributes\TestParametersFactory;
use Tests\src\Support\Doubles\Stubs\Attributes\TestRequestBodyFactory;
use Tests\src\Support\Doubles\Stubs\Attributes\TestResponsesFactory;
use Tests\src\Support\Doubles\Stubs\Builders\ExternalDocsFactory;
use Tests\src\Support\Doubles\Stubs\Servers\ServerWithMultipleVariableFormatting;
use Tests\src\Support\Doubles\Stubs\Tags\TagWithExternalObjectDoc;
use Tests\src\Support\Doubles\Stubs\Tags\TagWithoutExternalDoc;
use Workbench\App\Petstore\Security\SecuritySchemes\TestBearerSecuritySchemeFactory;
use Workbench\App\Petstore\Security\TestSingleHTTPBearerSchemeSecurityFactory;

describe(class_basename(OperationBuilder::class), function (): void {
    it('can be created with many combinations', function (RouteInfo $routeInfo, array $expected): void {
        $operationBuilder = app(OperationBuilder::class);

        $operation = $operationBuilder->build($routeInfo);

        expect($operation)->key()->toBe($routeInfo->method())
            ->value()->compile()->toBe($expected);
    })->with(
        [
            function (): array {
                $routeInfo = RouteInfo::create(
                    Route::get('test', static fn (): string => 'test'),
                );
                $routeInfo->actionAttributes = collect([
                    new OperationAttribute(
                        tags: [],
                        summary: '',
                        description: '',
                        deprecated: false,
                        security: null,
                        servers: [],
                        operationId: 'test',
                    ),
                ]);

                return [
                    'routeInfo' => $routeInfo,
                    'expected' => [
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
                        tags: [TagWithoutExternalDoc::class],
                        summary: 'summary',
                        description: 'description',
                        deprecated: true,
                        security: null,
                        servers: [],
                        operationId: 'test',
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
                    new Collection('test'),
                    new Extension(TestExtensionFactory::class),
                    new OperationAttribute(
                        tags: [TagWithExternalObjectDoc::class],
                        summary: 'summary',
                        description: 'description',
                        parameters: TestParametersFactory::class,
                        requestBody: TestRequestBodyFactory::class,
                        responses: TestResponsesFactory::class,
                        externalDocs: ExternalDocsFactory::class,
                        callbacks: TestCallbackFactory::class,
                        deprecated: true,
                        security: TestSingleHTTPBearerSchemeSecurityFactory::class,
                        servers: [ServerWithMultipleVariableFormatting::class],
                        operationId: 'test',
                    ),
                ]);

                return [
                    'routeInfo' => $routeInfo,
                    'expected' => [
                        'tags' => ['PostWithExternalObjectDoc'],
                        'summary' => 'summary',
                        'description' => 'description',
                        'externalDocs' => [
                            'url' => 'https://laragen.io/test',
                            'description' => 'description',
                        ],
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
                        'requestBody' => [
                            'content' => [
                                'application/json' => [],
                            ],
                        ],
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
                                'url' => 'https://laragen.io',
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
                                'https://laragen.io/' => [],
                            ],
                        ],
                        'x-key' => 'value',
                    ],
                ];
            },
        ],
    );
})->covers(OperationBuilder::class);
