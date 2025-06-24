<?php

namespace Tests\Unit\Collectors\Paths\Operations;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\CircularDependencyException;
use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation as AttributesOperation;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\Builders\SecurityBuilder;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\OperationBuilder;
use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\SecurityFactory;
use MohammadAlavi\LaravelOpenApi\Objects\RouteInfo;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Components;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Title;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Version;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\OpenAPI;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Path;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\AvailableOperation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\HttpMethod;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths\Paths;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\RequiredSecurity;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\SecurityRequirement;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\SecurityRequirements\TestApiKeySecurityRequirementFactory;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\SecurityRequirements\TestBearerSecurityRequirementFactory;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\SecuritySchemes\TestApiKeySecuritySchemeFactory;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\SecuritySchemes\TestBearerSecuritySchemeFactory;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\SecuritySchemes\TestOAuth2PasswordSecuritySchemeFactory;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\TestEmptySecurityFactory;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\TestSingleHTTPBearerSchemeSecurityFactory;

describe(class_basename(SecurityBuilder::class), function (): void {
    /** @return string[] */
    function bearerSecurityExpectations(): array
    {
        return [
            'type' => 'http',
            'description' => 'Example Security',
            'scheme' => 'bearer',
        ];
    }

    /** @return string[] */
    function apiKeySecurityExpectations(): array
    {
        return [
            'type' => 'apiKey',
            'name' => 'ApiKey Security',
            'in' => 'cookie',
        ];
    }

    function oAuth2SecurityExpectations(): array
    {
        return [
            'type' => 'oauth2',
            'description' => 'OAuth2 Password Security',
            'flows' => [
                'password' => [
                    'tokenUrl' => 'https://example.com/oauth/authorize',
                    'refreshUrl' => 'https://example.com/oauth/token',
                    'scopes' => [
                        'order' => 'Full information about orders.',
                        'order:item' => 'Information about items within an order.',
                        'order:payment' => 'Access to order payment details.',
                        'order:shipping:address' => 'Information about where to deliver orders.',
                        'order:shipping:status' => 'Information about the delivery status of orders.',
                    ],
                ],
            ],
        ];
    }

    it(
        'can apply multiple security schemes on operation',
        /**
         * @param SecuritySchemeFactory[] $securitySchemeFactories
         * @param class-string<SecurityFactory>|Security|null $topLevelSecurity
         * @param class-string<SecurityFactory>|null $operationSecurity
         *
         * @throws BindingResolutionException
         * @throws CircularDependencyException
         * @throws \JsonException
         */
        function (
            array $expectations,
            array $securitySchemeFactories,
            string|Security|null $topLevelSecurity,
            string|null $operationSecurity,
        ): void {
            $components = Components::create()->securitySchemes(...$securitySchemeFactories);

            $route = '/foo';
            $action = 'get';
            $routeInformation = RouteInfo::create(
                Route::$action($route, static fn (): string => 'example'),
            );
            $routeInformation->actionAttributes = collect([
                new AttributesOperation(security: $operationSecurity),
            ]);
            $operation = app(OperationBuilder::class)->build($routeInformation);

            $openApi = OpenAPI::v311(
                Info::create(
                    Title::create('Example API'),
                    Version::create('1.0'),
                ),
            )->components($components)
                ->paths(
                    Paths::create(
                        Path::create(
                            $route,
                            PathItem::create()
                                ->operations($operation),
                        ),
                    ),
                );
            if ($topLevelSecurity) {
                $openApi = $openApi->security(
                    is_a(
                        $topLevelSecurity,
                        SecurityFactory::class,
                        true,
                    ) ? app($topLevelSecurity)->build() : $topLevelSecurity,
                );
            }

            // Assert that the generated JSON matches the expected JSON for this scenario
            $actionData = [
                $action => [],
            ];
            if (!is_null($expectations['pathSecurity'])) {
                $actionData[$action] = ['security' => $expectations['pathSecurity']];
            }

            $collectionData = [
                'components' => $expectations['components'],
            ];
            if (!is_null($expectations['topLevelSecurity'])) {
                $collectionData['security'] = $expectations['topLevelSecurity'];
            }

            $this->assertSame([
                'openapi' => '3.1.1',
                'info' => [
                    'title' => 'Example API',
                    'version' => '1.0',
                ],
                'jsonSchemaDialect' => 'https://spec.openapis.org/oas/3.1/dialect/base',
                'servers' => [
                    [
                        'url' => '/',
                    ],
                ],
                'paths' => [
                    $route => $actionData,
                ], ...$collectionData,
            ], $openApi->asArray());
        },
    )->with(
        [
            'No global security - no path security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            TestBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                            TestOAuth2PasswordSecuritySchemeFactory::name() => oAuth2SecurityExpectations(),
                        ],
                    ],
                    'topLevelSecurity' => null,
                    'pathSecurity' => null,
                ],
                [ // available global securities (components)
                    TestBearerSecuritySchemeFactory::create(),
                    TestApiKeySecuritySchemeFactory::create(),
                    TestOAuth2PasswordSecuritySchemeFactory::create(),
                ],
                null, // applied global security
                null, // use default global securities
            ],
            'Use default global security - have single class string security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                            TestBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                        ],
                    ],
                    'topLevelSecurity' => [
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                    ],
                    'pathSecurity' => null,
                ],
                [
                    TestApiKeySecuritySchemeFactory::create(),
                    TestBearerSecuritySchemeFactory::create(),
                ],
                Security::create(TestApiKeySecurityRequirementFactory::create()),
                null,
            ],
            'Use default global security - have multi-auth security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                            TestOAuth2PasswordSecuritySchemeFactory::name() => oAuth2SecurityExpectations(),
                            TestBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                        ],
                    ],
                    'topLevelSecurity' => [
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                            TestBearerSecuritySchemeFactory::name() => [],
                        ],
                        [
                            TestBearerSecuritySchemeFactory::name() => [],
                        ],
                        [
                            TestBearerSecuritySchemeFactory::name() => [],
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                        [
                            TestBearerSecuritySchemeFactory::name() => [],
                            TestApiKeySecuritySchemeFactory::name() => [],
                            TestOAuth2PasswordSecuritySchemeFactory::name() => [],
                        ],
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                    ],
                    'pathSecurity' => null,
                ],
                [
                    TestApiKeySecuritySchemeFactory::create(),
                    TestOAuth2PasswordSecuritySchemeFactory::create(),
                    TestBearerSecuritySchemeFactory::create(),
                ],
                Security::create(
                    SecurityRequirement::create(
                        RequiredSecurity::create(
                            TestApiKeySecuritySchemeFactory::create(),
                        ),
                    ),
                    SecurityRequirement::create(
                        RequiredSecurity::create(
                            TestApiKeySecuritySchemeFactory::create(),
                        ),
                        RequiredSecurity::create(
                            TestBearerSecuritySchemeFactory::create(),
                        ),
                    ),
                    SecurityRequirement::create(
                        RequiredSecurity::create(
                            TestBearerSecuritySchemeFactory::create(),
                        ),
                    ),
                    SecurityRequirement::create(
                        RequiredSecurity::create(
                            TestBearerSecuritySchemeFactory::create(),
                        ),
                        RequiredSecurity::create(
                            TestApiKeySecuritySchemeFactory::create(),
                        ),
                    ),
                    SecurityRequirement::create(
                        RequiredSecurity::create(
                            TestBearerSecuritySchemeFactory::create(),
                        ),
                        RequiredSecurity::create(
                            TestApiKeySecuritySchemeFactory::create(),
                        ),
                        RequiredSecurity::create(
                            TestOAuth2PasswordSecuritySchemeFactory::create(),
                        ),
                    ),
                    // TODO: should this duplication be removed?
                    //  I don't think it is removed automatically.
                    SecurityRequirement::create(
                        RequiredSecurity::create(
                            TestApiKeySecuritySchemeFactory::create(),
                        ),
                    ),
                ),
                null,
            ],
            'Override global security - disable global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                        ],
                    ],
                    'topLevelSecurity' => [
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                    ],
                    'pathSecurity' => [],
                ],
                [
                    TestApiKeySecuritySchemeFactory::create(),
                ],
                Security::create(
                    SecurityRequirement::create(
                        RequiredSecurity::create(
                            TestApiKeySecuritySchemeFactory::create(),
                        ),
                    ),
                ),
                TestEmptySecurityFactory::class,
            ],
            'Override global security - with same security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            TestBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                        ],
                    ],
                    'topLevelSecurity' => [
                        [
                            TestBearerSecuritySchemeFactory::name() => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            TestBearerSecuritySchemeFactory::name() => [],
                        ],
                    ],
                ],
                [
                    TestBearerSecuritySchemeFactory::create(), // available global securities (components)
                ],
                TestSingleHTTPBearerSchemeSecurityFactory::class, // applied global securities
                TestSingleHTTPBearerSchemeSecurityFactory::class, // security overrides
            ],
            'Override global security - single auth class string' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            TestBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                        ],
                    ],
                    'topLevelSecurity' => [
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            TestBearerSecuritySchemeFactory::name() => [],
                        ],
                    ],
                ],
                [
                    TestBearerSecuritySchemeFactory::create(),
                    TestApiKeySecuritySchemeFactory::create(),
                ],
                Security::create(
                    SecurityRequirement::create(
                        RequiredSecurity::create(
                            TestApiKeySecuritySchemeFactory::create(),
                        ),
                    ),
                ),
                TestSingleHTTPBearerSchemeSecurityFactory::class,
            ],
            'Override global security - single auth array' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            TestBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                        ],
                    ],
                    'topLevelSecurity' => [
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            TestBearerSecuritySchemeFactory::name() => [],
                        ],
                    ],
                ],
                [
                    TestBearerSecuritySchemeFactory::create(),
                    TestApiKeySecuritySchemeFactory::create(),
                ],
                Security::create( // applied global securities
                    SecurityRequirement::create(
                        RequiredSecurity::create(
                            TestApiKeySecuritySchemeFactory::create(),
                        ),
                    ),
                ),
                TestSingleHTTPBearerSchemeSecurityFactory::class,
            ],
            'Override global security - multi-auth (and) - single auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            TestBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                            TestOAuth2PasswordSecuritySchemeFactory::name() => oAuth2SecurityExpectations(),
                        ],
                    ],
                    'topLevelSecurity' => [
                        [
                            TestBearerSecuritySchemeFactory::name() => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                            TestBearerSecuritySchemeFactory::name() => [],
                        ],
                    ],
                ],
                [
                    TestBearerSecuritySchemeFactory::create(),
                    TestApiKeySecuritySchemeFactory::create(),
                    TestOAuth2PasswordSecuritySchemeFactory::create(),
                ],
                TestSingleHTTPBearerSchemeSecurityFactory::class,
                (new class implements SecurityFactory {
                    public function build(): Security
                    {
                        return Security::create(
                            SecurityRequirement::create(
                                RequiredSecurity::create(
                                    TestApiKeySecuritySchemeFactory::create(),
                                ),
                                RequiredSecurity::create(
                                    TestBearerSecuritySchemeFactory::create(),
                                ),
                            ),
                        );
                    }
                })::class,
            ],
            'Override global security - multi-auth (and) - multi auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            TestBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                            TestOAuth2PasswordSecuritySchemeFactory::name() => oAuth2SecurityExpectations(),
                        ],
                    ],
                    'topLevelSecurity' => [
                        [
                            TestBearerSecuritySchemeFactory::name() => [],
                            TestOAuth2PasswordSecuritySchemeFactory::name() => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            TestBearerSecuritySchemeFactory::name() => [],
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                    ],
                ],
                [
                    TestBearerSecuritySchemeFactory::create(),
                    TestApiKeySecuritySchemeFactory::create(),
                    TestOAuth2PasswordSecuritySchemeFactory::create(),
                ],
                (new class implements SecurityFactory {
                    public function build(): Security
                    {
                        return Security::create(
                            SecurityRequirement::create(
                                RequiredSecurity::create(
                                    TestBearerSecuritySchemeFactory::create(),
                                ),
                                RequiredSecurity::create(
                                    TestOAuth2PasswordSecuritySchemeFactory::create(),
                                ),
                            ),
                        );
                    }
                })::class,
                (new class implements SecurityFactory {
                    public function build(): Security
                    {
                        return Security::create(
                            SecurityRequirement::create(
                                RequiredSecurity::create(
                                    TestBearerSecuritySchemeFactory::create(),
                                ),
                                RequiredSecurity::create(
                                    TestApiKeySecuritySchemeFactory::create(),
                                ),
                            ),
                        );
                    }
                })::class,
            ],
            'Override global security - multi-auth (or) - single auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            TestBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                            TestOAuth2PasswordSecuritySchemeFactory::name() => oAuth2SecurityExpectations(),
                        ],
                    ],
                    'topLevelSecurity' => [
                        [
                            TestBearerSecuritySchemeFactory::name() => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            TestBearerSecuritySchemeFactory::name() => [],
                        ],
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                    ],
                ],
                [
                    TestBearerSecuritySchemeFactory::create(),
                    TestApiKeySecuritySchemeFactory::create(),
                    TestOAuth2PasswordSecuritySchemeFactory::create(),
                ],
                TestSingleHTTPBearerSchemeSecurityFactory::class,
                (new class implements SecurityFactory {
                    public function build(): Security
                    {
                        return Security::create(
                            SecurityRequirement::create(
                                RequiredSecurity::create(
                                    TestBearerSecuritySchemeFactory::create(),
                                ),
                            ),
                            SecurityRequirement::create(
                                RequiredSecurity::create(
                                    TestApiKeySecuritySchemeFactory::create(),
                                ),
                            ),
                        );
                    }
                })::class,
            ],
            'Override global security - multi-auth (or) - multi auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            TestBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                            TestOAuth2PasswordSecuritySchemeFactory::name() => oAuth2SecurityExpectations(),
                        ],
                    ],
                    'topLevelSecurity' => [
                        [
                            TestBearerSecuritySchemeFactory::name() => [],
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            TestBearerSecuritySchemeFactory::name() => [],
                        ],
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                    ],
                ],
                [
                    TestBearerSecuritySchemeFactory::create(),
                    TestApiKeySecuritySchemeFactory::create(),
                    TestOAuth2PasswordSecuritySchemeFactory::create(),
                ],
                (new class implements SecurityFactory {
                    public function build(): Security
                    {
                        return Security::create(
                            SecurityRequirement::create(
                                RequiredSecurity::create(
                                    TestBearerSecuritySchemeFactory::create(),
                                ),
                                RequiredSecurity::create(
                                    TestApiKeySecuritySchemeFactory::create(),
                                ),
                            ),
                        );
                    }
                })::class,
                (new class implements SecurityFactory {
                    public function build(): Security
                    {
                        return Security::create(
                            SecurityRequirement::create(
                                RequiredSecurity::create(
                                    TestBearerSecuritySchemeFactory::create(),
                                ),
                            ),
                            SecurityRequirement::create(
                                RequiredSecurity::create(
                                    TestApiKeySecuritySchemeFactory::create(),
                                ),
                            ),
                        );
                    }
                })::class,
            ],
            'Override global security - multi-auth (and + or) - single auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            TestOAuth2PasswordSecuritySchemeFactory::name() => oAuth2SecurityExpectations(),
                            TestBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                        ],
                    ],
                    'topLevelSecurity' => [
                        [
                            TestBearerSecuritySchemeFactory::name() => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                        [
                            TestBearerSecuritySchemeFactory::name() => [],
                            TestOAuth2PasswordSecuritySchemeFactory::name() => [],
                        ],
                    ],
                ],
                [
                    TestOAuth2PasswordSecuritySchemeFactory::create(),
                    TestBearerSecuritySchemeFactory::create(),
                    TestApiKeySecuritySchemeFactory::create(),
                ],
                TestSingleHTTPBearerSchemeSecurityFactory::class,
                (new class implements SecurityFactory {
                    public function build(): Security
                    {
                        return Security::create(
                            SecurityRequirement::create(
                                RequiredSecurity::create(
                                    TestApiKeySecuritySchemeFactory::create(),
                                ),
                            ),
                            SecurityRequirement::create(
                                RequiredSecurity::create(
                                    TestBearerSecuritySchemeFactory::create(),
                                ),
                                RequiredSecurity::create(
                                    TestOAuth2PasswordSecuritySchemeFactory::create(),
                                ),
                            ),
                        );
                    }
                })::class,
            ],
            'Override global security - multi-auth (and + or) - multi auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            TestOAuth2PasswordSecuritySchemeFactory::name() => oAuth2SecurityExpectations(),
                            TestBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                        ],
                    ],
                    'topLevelSecurity' => [
                        [
                            TestBearerSecuritySchemeFactory::name() => [],
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            TestBearerSecuritySchemeFactory::name() => [],
                        ],
                        [
                            TestBearerSecuritySchemeFactory::name() => [],
                            TestOAuth2PasswordSecuritySchemeFactory::name() => [],
                        ],
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                    ],
                ],
                [
                    TestOAuth2PasswordSecuritySchemeFactory::create(),
                    TestBearerSecuritySchemeFactory::create(),
                    TestApiKeySecuritySchemeFactory::create(),
                ],
                (new class implements SecurityFactory {
                    public function build(): Security
                    {
                        return Security::create(
                            SecurityRequirement::create(
                                RequiredSecurity::create(
                                    TestBearerSecuritySchemeFactory::create(),
                                ),
                                RequiredSecurity::create(
                                    TestApiKeySecuritySchemeFactory::create(),
                                ),
                            ),
                        );
                    }
                })::class,
                (new class implements SecurityFactory {
                    public function build(): Security
                    {
                        return Security::create(
                            SecurityRequirement::create(
                                RequiredSecurity::create(
                                    TestBearerSecuritySchemeFactory::create(),
                                ),
                            ),
                            SecurityRequirement::create(
                                RequiredSecurity::create(
                                    TestBearerSecuritySchemeFactory::create(),
                                ),
                                RequiredSecurity::create(
                                    TestOAuth2PasswordSecuritySchemeFactory::create(),
                                ),
                            ),
                            SecurityRequirement::create(
                                RequiredSecurity::create(
                                    TestApiKeySecuritySchemeFactory::create(),
                                ),
                            ),
                        );
                    }
                })::class,
            ],
        ],
    );

    it(
        'can apply multiple security schemes globally',
        /**
         * @param class-string<SecurityFactory>|Security $topLevelSecurity
         */
        function (
            array $expectedJson,
            array $securitySchemeFactories,
            string|Security $topLevelSecurity,
        ): void {
            $components = Components::create()->securitySchemes(...$securitySchemeFactories);

            $operation = Operation::create();

            $openApi = OpenAPI::v311(
                Info::create(
                    Title::create('Example API'),
                    Version::create('1.0'),
                ),
            )->security(
                is_a(
                    $topLevelSecurity,
                    SecurityFactory::class,
                    true,
                ) ? app($topLevelSecurity)->build() : $topLevelSecurity,
            )->components($components)
                ->paths(
                    Paths::create(
                        Path::create(
                            '/foo',
                            PathItem::create()
                                ->operations(
                                    AvailableOperation::create(
                                        HttpMethod::GET,
                                        $operation,
                                    ),
                                ),
                        ),
                    ),
                );

            // Assert that the generated JSON matches the expected JSON for this scenario
            $expected = [
                'openapi' => '3.1.1',
                'info' => [
                    'title' => 'Example API',
                    'version' => '1.0',
                ],
                'jsonSchemaDialect' => 'https://spec.openapis.org/oas/3.1/dialect/base',
                'servers' => [
                    [
                        'url' => '/',
                    ],
                ],
                'paths' => [
                    '/foo' => [
                        'get' => [
                        ],
                    ],
                ],
                'components' => $expectedJson['components'],
                'security' => $expectedJson['security'],
            ];
            $this->assertSame($expected, $openApi->asArray());
        },
    )->with([
        'JWT authentication only' => [
            [
                'components' => [
                    'securitySchemes' => [
                        TestBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                    ],
                ],
                'security' => [
                    [
                        TestBearerSecuritySchemeFactory::name() => [],
                    ],
                ],
            ],
            [
                TestBearerSecuritySchemeFactory::create(),
            ],
            TestSingleHTTPBearerSchemeSecurityFactory::class,
        ],
        'ApiKey authentication only' => [
            [
                'components' => [
                    'securitySchemes' => [
                        TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                    ],
                ],
                'security' => [
                    [
                        TestApiKeySecuritySchemeFactory::name() => [],
                    ],
                ],
            ],
            [
                TestApiKeySecuritySchemeFactory::create(),
            ],
            Security::create(TestApiKeySecurityRequirementFactory::create()),
        ],
        'Both JWT and ApiKey authentication required' => [
            [
                'components' => [
                    'securitySchemes' => [
                        TestBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                        TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                    ],
                ],
                'security' => [
                    [
                        TestBearerSecuritySchemeFactory::name() => [],
                        TestApiKeySecuritySchemeFactory::name() => [],
                    ],
                ],
            ],
            [
                TestBearerSecuritySchemeFactory::create(),
                TestApiKeySecuritySchemeFactory::create(),
            ],
            Security::create(
                SecurityRequirement::create(
                    RequiredSecurity::create(
                        TestBearerSecuritySchemeFactory::create(),
                    ),
                    RequiredSecurity::create(
                        TestApiKeySecuritySchemeFactory::create(),
                    ),
                ),
            ),
        ],
        'Either JWT or ApiKey authentication required' => [
            [
                'components' => [
                    'securitySchemes' => [
                        TestBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                        TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                    ],
                ],
                'security' => [
                    [
                        TestBearerSecuritySchemeFactory::name() => [],
                    ],
                    [
                        TestApiKeySecuritySchemeFactory::name() => [],
                    ],
                ],
            ],
            [
                TestBearerSecuritySchemeFactory::create(),
                TestApiKeySecuritySchemeFactory::create(),
            ],
            Security::create(
                TestBearerSecurityRequirementFactory::create(),
                TestApiKeySecurityRequirementFactory::create(),
            ),
        ],
        'And & Or combination' => [
            [
                'components' => [
                    'securitySchemes' => [
                        TestBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                        TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                        TestOAuth2PasswordSecuritySchemeFactory::name() => oAuth2SecurityExpectations(),
                    ],
                ],
                'security' => [
                    [
                        TestBearerSecuritySchemeFactory::name() => [],
                    ],
                    [
                        TestBearerSecuritySchemeFactory::name() => [],
                        TestOAuth2PasswordSecuritySchemeFactory::name() => [],
                    ],
                    [
                        TestBearerSecuritySchemeFactory::name() => [],
                    ],
                    [
                        TestBearerSecuritySchemeFactory::name() => [],
                    ],
                    [
                        TestApiKeySecuritySchemeFactory::name() => [],
                    ],
                    [
                        TestApiKeySecuritySchemeFactory::name() => [],
                    ],
                ],
            ],
            [
                TestBearerSecuritySchemeFactory::create(),
                TestApiKeySecuritySchemeFactory::create(),
                TestOAuth2PasswordSecuritySchemeFactory::create(),
            ],
            Security::create(
                SecurityRequirement::create(
                    RequiredSecurity::create(
                        TestBearerSecuritySchemeFactory::create(),
                    ),
                ),
                SecurityRequirement::create(
                    RequiredSecurity::create(
                        TestBearerSecuritySchemeFactory::create(),
                    ),
                    RequiredSecurity::create(
                        TestOAuth2PasswordSecuritySchemeFactory::create(),
                    ),
                ),
                SecurityRequirement::create(
                    RequiredSecurity::create(
                        TestBearerSecuritySchemeFactory::create(),
                    ),
                ),
                SecurityRequirement::create(
                    RequiredSecurity::create(
                        TestBearerSecuritySchemeFactory::create(),
                    ),
                ),
                SecurityRequirement::create(
                    RequiredSecurity::create(
                        TestApiKeySecuritySchemeFactory::create(),
                    ),
                ),
                SecurityRequirement::create(
                    RequiredSecurity::create(
                        TestApiKeySecuritySchemeFactory::create(),
                    ),
                ),
            ),
        ],
    ]);

    it('can buildup the security scheme', function (): void {
        $components = Components::create()
            ->securitySchemes(TestBearerSecuritySchemeFactory::create());

        $operation = AvailableOperation::create(
            HttpMethod::GET,
            Operation::create()
                ->responses(
                    Responses::create(
                        ResponseEntry::create(
                            HTTPStatusCode::ok(),
                            Response::create(Description::create('OK')),
                        ),
                    ),
                ),
        );

        $openApi = OpenAPI::v311(
            Info::create(
                Title::create('Example API'),
                Version::create('1.0'),
            ),
        )->security(app(TestSingleHTTPBearerSchemeSecurityFactory::class)->build())
            ->components($components)
            ->paths(
                Paths::create(
                    Path::create(
                        '/foo',
                        PathItem::create()
                            ->operations($operation),
                    ),
                ),
            );

        $expected = [
            'openapi' => '3.1.1',
            'info' => [
                'title' => 'Example API',
                'version' => '1.0',
            ],
            'jsonSchemaDialect' => 'https://spec.openapis.org/oas/3.1/dialect/base',
            'servers' => [
                [
                    'url' => '/',
                ],
            ],
            'paths' => [
                '/foo' => [
                    'get' => [
                        'responses' => [
                            '200' => [
                                'description' => 'OK',
                            ],
                        ],
                    ],
                ],
            ],
            'components' => [
                'securitySchemes' => [
                    TestBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                ],
            ],
            'security' => [
                [
                    TestBearerSecuritySchemeFactory::name() => [],
                ],
            ],
        ];
        $this->assertSame($expected, $openApi->asArray());
    });

    it('can add operation security using builder', function (): void {
        $components = Components::create()
            ->securitySchemes(TestBearerSecuritySchemeFactory::create());

        $routeInformation = RouteInfo::create(
            Route::get('/example', static fn (): string => 'example'),
        );
        $routeInformation->actionAttributes = collect([
            new AttributesOperation(security: TestSingleHTTPBearerSchemeSecurityFactory::class),
        ]);

        $securityBuilder = app(SecurityBuilder::class);

        $operation = AvailableOperation::create(
            HttpMethod::PATCH,
            Operation::create()
                ->responses(
                    Responses::create(
                        ResponseEntry::create(
                            HTTPStatusCode::ok(),
                            Response::create(Description::create('OK')),
                        ),
                    ),
                )->security(
                    $securityBuilder->build(
                        $routeInformation->operationAttribute()->security,
                    ),
                ),
        );

        $openApi = OpenAPI::v311(
            Info::create(
                Title::create('Example API'),
                Version::create('1.0'),
            ),
        )->components($components)
            ->paths(
                Paths::create(
                    Path::create(
                        '/foo',
                        PathItem::create()
                            ->operations($operation),
                    ),
                ),
            );

        $expected = [
            'openapi' => '3.1.1',
            'info' => [
                'title' => 'Example API',
                'version' => '1.0',
            ],
            'jsonSchemaDialect' => 'https://spec.openapis.org/oas/3.1/dialect/base',
            'servers' => [
                [
                    'url' => '/',
                ],
            ],
            'paths' => [
                '/foo' => [
                    'patch' => [
                        'responses' => [
                            '200' => [
                                'description' => 'OK',
                            ],
                        ],
                        'security' => [
                            [
                                TestBearerSecuritySchemeFactory::name() => [],
                            ],
                        ],
                    ],
                ],
            ],
            'components' => [
                'securitySchemes' => [
                    TestBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                ],
            ],
        ];

        expect($openApi->asArray())->toBe($expected);
    });
})->covers(SecurityBuilder::class);
