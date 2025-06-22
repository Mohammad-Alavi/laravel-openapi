<?php

namespace Tests\Unit\Collectors\Paths\Operations;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\CircularDependencyException;
use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation as AttributesOperation;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\Builders\SecurityBuilder;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\OperationBuilder;
use MohammadAlavi\LaravelOpenApi\Objects\RouteInfo;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\SecurityFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Components;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Title;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Version;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\OpenAPI;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Path;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths\Paths;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\RequiredSecurity;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\SecurityRequirement;
use Tests\Doubles\Stubs\Petstore\Security\ExampleNoSecurityRequirementSecurity;
use Tests\Doubles\Stubs\Petstore\Security\SecurityRequirements\TestApiKeySecurityRequirementFactory;
use Tests\Doubles\Stubs\Petstore\Security\SecurityRequirements\TestBearerSecurityRequirementFactory;
use Tests\Doubles\Stubs\Petstore\Security\SecuritySchemes\TestApiKeySecuritySchemeFactory;
use Tests\Doubles\Stubs\Petstore\Security\SecuritySchemes\TestHTTPBearerSecuritySchemeFactory;
use Tests\Doubles\Stubs\Petstore\Security\SecuritySchemes\TestOAuth2PasswordSecuritySchemeFactory;
use Tests\Doubles\Stubs\Petstore\Security\TestSingleHTTPBearerSchemeSecurityFactory;

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
         * @throws BindingResolutionException
         * @throws CircularDependencyException
         * @throws \JsonException
         */
        function (
            array $expectations,
            array $securitySchemeFactories,
            Security|null $topLevelSecurity,
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
                $openApi = $openApi->security($topLevelSecurity);
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
            if (!is_null($expectations['globalSecurity'])) {
                $collectionData['security'] = $expectations['globalSecurity'];
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
                            TestHTTPBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                            'OAuth2Password' => oAuth2SecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => null,
                    'pathSecurity' => null,
                ],
                [ // available global securities (components)
                    TestHTTPBearerSecuritySchemeFactory::create(),
                    TestApiKeySecuritySchemeFactory::create(),
                    TestHTTPBearerSecuritySchemeFactory::create(),
                ],
                null, // applied global security
                null, // use default global securities
            ],
            'Use default global security - have single class string security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                            TestHTTPBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                    ],
                    'pathSecurity' => null,
                ],
                [
                    TestApiKeySecuritySchemeFactory::create(),
                    TestHTTPBearerSecuritySchemeFactory::create(),
                ],
                TestApiKeySecuritySchemeFactory::create(),
                null,
            ],
            'Use default global security - have multi-auth security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                            'OAuth2Password' => oAuth2SecurityExpectations(),
                            TestHTTPBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                            TestHTTPBearerSecuritySchemeFactory::name() => [],
                        ],
                        [
                            TestHTTPBearerSecuritySchemeFactory::name() => [],
                        ],
                        [
                            TestHTTPBearerSecuritySchemeFactory::name() => [],
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                        [
                            TestHTTPBearerSecuritySchemeFactory::name() => [],
                            TestApiKeySecuritySchemeFactory::name() => [],
                            'OAuth2Password' => [],
                        ],
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                    ],
                    'pathSecurity' => null,
                ],
                [
                    TestApiKeySecuritySchemeFactory::create(),
                    TestHTTPBearerSecuritySchemeFactory::create(),
                    TestHTTPBearerSecuritySchemeFactory::create(),
                ],
                [
                    TestApiKeySecuritySchemeFactory::create(),
                    [
                        TestApiKeySecuritySchemeFactory::create(),
                        TestHTTPBearerSecuritySchemeFactory::create(),
                    ],
                    TestHTTPBearerSecuritySchemeFactory::create(),
                    [
                        TestHTTPBearerSecuritySchemeFactory::create(),
                        TestApiKeySecuritySchemeFactory::create(),
                    ],
                    [
                        TestHTTPBearerSecuritySchemeFactory::create(),
                        TestApiKeySecuritySchemeFactory::create(),
                        TestHTTPBearerSecuritySchemeFactory::create(),
                    ],
                    [
                        // TODO: should this duplication be removed?
                        //  I don't think it is removed automatically.
                        TestApiKeySecuritySchemeFactory::create(),
                    ],
                ],
                null,
            ],
            'Override global security - disable global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                    ],
                    'pathSecurity' => [],
                ],
                [
                    TestApiKeySecuritySchemeFactory::create(),
                ],
                TestApiKeySecuritySchemeFactory::create(),
                ExampleNoSecurityRequirementSecurity::class,
            ],
            'Override global security - with same security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            TestHTTPBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            TestHTTPBearerSecuritySchemeFactory::name() => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            TestHTTPBearerSecuritySchemeFactory::name() => [],
                        ],
                    ],
                ],
                [
                    TestHTTPBearerSecuritySchemeFactory::create(), // available global securities (components)
                ],
                app(TestSingleHTTPBearerSchemeSecurityFactory::class)->object(), // applied global securities
                TestHTTPBearerSecuritySchemeFactory::class, // security overrides
            ],
            'Override global security - single auth class string' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            TestHTTPBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            TestHTTPBearerSecuritySchemeFactory::name() => [],
                        ],
                    ],
                ],
                [
                    TestHTTPBearerSecuritySchemeFactory::create(),
                    TestApiKeySecuritySchemeFactory::create(),
                ],
                TestApiKeySecuritySchemeFactory::create(),
                TestHTTPBearerSecuritySchemeFactory::class,
            ],
            'Override global security - single auth array' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            TestHTTPBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            TestHTTPBearerSecuritySchemeFactory::name() => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                    ],
                ],
                [
                    TestHTTPBearerSecuritySchemeFactory::create(),
                    TestApiKeySecuritySchemeFactory::create(),
                ],
                app(TestSingleHTTPBearerSchemeSecurityFactory::class)->object(), // applied global securities
                [
                    TestApiKeySecuritySchemeFactory::class,
                ],
            ],
            'Override global security - multi-auth (and) - single auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            TestHTTPBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                            'OAuth2Password' => oAuth2SecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            TestHTTPBearerSecuritySchemeFactory::name() => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                            TestHTTPBearerSecuritySchemeFactory::name() => [],
                        ],
                    ],
                ],
                [
                    TestHTTPBearerSecuritySchemeFactory::create(),
                    TestApiKeySecuritySchemeFactory::create(),
                    TestHTTPBearerSecuritySchemeFactory::create(),
                ],
                [
                    TestHTTPBearerSecuritySchemeFactory::create(),
                ],
                [
                    [
                        TestApiKeySecuritySchemeFactory::create(),
                        TestHTTPBearerSecuritySchemeFactory::create(),
                    ],
                ],
            ],
            'Override global security - multi-auth (and) - multi auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            TestHTTPBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                            'OAuth2Password' => oAuth2SecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            TestHTTPBearerSecuritySchemeFactory::name() => [],
                            'OAuth2Password' => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            TestHTTPBearerSecuritySchemeFactory::name() => [],
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                    ],
                ],
                [
                    TestHTTPBearerSecuritySchemeFactory::create(),
                    TestApiKeySecuritySchemeFactory::create(),
                    TestHTTPBearerSecuritySchemeFactory::create(),
                ],
                [
                    [
                        TestHTTPBearerSecuritySchemeFactory::create(),
                        TestHTTPBearerSecuritySchemeFactory::create(),
                    ],
                ],
                [
                    [
                        TestHTTPBearerSecuritySchemeFactory::create(),
                        TestApiKeySecuritySchemeFactory::create(),
                    ],
                ],
            ],
            'Override global security - multi-auth (or) - single auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            TestHTTPBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                            'OAuth2Password' => oAuth2SecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            TestHTTPBearerSecuritySchemeFactory::name() => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            TestHTTPBearerSecuritySchemeFactory::name() => [],
                        ],
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                    ],
                ],
                [
                    TestHTTPBearerSecuritySchemeFactory::create(),
                    TestApiKeySecuritySchemeFactory::create(),
                    TestHTTPBearerSecuritySchemeFactory::create(),
                ],
                [
                    TestHTTPBearerSecuritySchemeFactory::create(),
                ],
                [
                    [
                        TestHTTPBearerSecuritySchemeFactory::create(),
                    ],
                    [
                        TestApiKeySecuritySchemeFactory::create(),
                    ],
                ],
            ],
            'Override global security - multi-auth (or) - multi auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            TestHTTPBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                            'OAuth2Password' => oAuth2SecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            TestHTTPBearerSecuritySchemeFactory::name() => [],
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            TestHTTPBearerSecuritySchemeFactory::name() => [],
                        ],
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                    ],
                ],
                [
                    TestHTTPBearerSecuritySchemeFactory::create(),
                    TestApiKeySecuritySchemeFactory::create(),
                    TestHTTPBearerSecuritySchemeFactory::create(),
                ],
                [
                    [
                        TestHTTPBearerSecuritySchemeFactory::create(),
                        TestApiKeySecuritySchemeFactory::create(),
                    ],
                ],
                [
                    [
                        TestHTTPBearerSecuritySchemeFactory::create(),
                    ],
                    [
                        TestApiKeySecuritySchemeFactory::create(),
                    ],
                ],
            ],
            'Override global security - multi-auth (and + or) - single auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'OAuth2Password' => oAuth2SecurityExpectations(),
                            TestHTTPBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            TestHTTPBearerSecuritySchemeFactory::name() => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                        [
                            TestHTTPBearerSecuritySchemeFactory::name() => [],
                            'OAuth2Password' => [],
                        ],
                    ],
                ],
                [
                    TestHTTPBearerSecuritySchemeFactory::create(),
                    TestHTTPBearerSecuritySchemeFactory::create(),
                    TestApiKeySecuritySchemeFactory::create(),
                ],
                [
                    TestHTTPBearerSecuritySchemeFactory::create(),
                ],
                [
                    TestApiKeySecuritySchemeFactory::create(),
                    [
                        TestHTTPBearerSecuritySchemeFactory::create(),
                        TestHTTPBearerSecuritySchemeFactory::create(),
                    ],
                ],
            ],
            'Override global security - multi-auth (and + or) - multi auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'OAuth2Password' => oAuth2SecurityExpectations(),
                            TestHTTPBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                            TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            TestHTTPBearerSecuritySchemeFactory::name() => [],
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            TestHTTPBearerSecuritySchemeFactory::name() => [],
                        ],
                        [
                            TestHTTPBearerSecuritySchemeFactory::name() => [],
                            'OAuth2Password' => [],
                        ],
                        [
                            TestApiKeySecuritySchemeFactory::name() => [],
                        ],
                    ],
                ],
                [
                    TestHTTPBearerSecuritySchemeFactory::create(),
                    TestHTTPBearerSecuritySchemeFactory::create(),
                    TestApiKeySecuritySchemeFactory::create(),
                ],
                [
                    [
                        TestHTTPBearerSecuritySchemeFactory::create(),
                        TestApiKeySecuritySchemeFactory::create(),
                    ],
                ],
                [
                    [
                        TestHTTPBearerSecuritySchemeFactory::create(),
                    ],
                    [
                        TestHTTPBearerSecuritySchemeFactory::create(),
                        TestHTTPBearerSecuritySchemeFactory::create(),
                    ],
                    [
                        TestApiKeySecuritySchemeFactory::create(),
                    ],
                ],
            ],
        ],
    )->skip();

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

            $operation = Operation::create()
                ->action('get');

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
                ) ? app($topLevelSecurity)->object() : $topLevelSecurity,
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
                        TestHTTPBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                    ],
                ],
                'security' => [
                    [
                        TestHTTPBearerSecuritySchemeFactory::name() => [],
                    ],
                ],
            ],
            [
                TestHTTPBearerSecuritySchemeFactory::create(),
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
                        TestHTTPBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                        TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                    ],
                ],
                'security' => [
                    [
                        TestHTTPBearerSecuritySchemeFactory::name() => [],
                        TestApiKeySecuritySchemeFactory::name() => [],
                    ],
                ],
            ],
            [
                TestHTTPBearerSecuritySchemeFactory::create(),
                TestApiKeySecuritySchemeFactory::create(),
            ],
            Security::create(
                SecurityRequirement::create(
                    RequiredSecurity::create(
                        TestHTTPBearerSecuritySchemeFactory::create(),
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
                        TestHTTPBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                        TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                    ],
                ],
                'security' => [
                    [
                        TestHTTPBearerSecuritySchemeFactory::name() => [],
                    ],
                    [
                        TestApiKeySecuritySchemeFactory::name() => [],
                    ],
                ],
            ],
            [
                TestHTTPBearerSecuritySchemeFactory::create(),
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
                        TestHTTPBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                        TestApiKeySecuritySchemeFactory::name() => apiKeySecurityExpectations(),
                        TestOAuth2PasswordSecuritySchemeFactory::name() => oAuth2SecurityExpectations(),
                    ],
                ],
                'security' => [
                    [
                        TestHTTPBearerSecuritySchemeFactory::name() => [],
                    ],
                    [
                        TestHTTPBearerSecuritySchemeFactory::name() => [],
                        TestOAuth2PasswordSecuritySchemeFactory::name() => [],
                    ],
                    [
                        TestHTTPBearerSecuritySchemeFactory::name() => [],
                    ],
                    [
                        TestHTTPBearerSecuritySchemeFactory::name() => [],
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
                TestHTTPBearerSecuritySchemeFactory::create(),
                TestApiKeySecuritySchemeFactory::create(),
                TestOAuth2PasswordSecuritySchemeFactory::create(),
            ],
            Security::create(
                SecurityRequirement::create(
                    RequiredSecurity::create(
                        TestHTTPBearerSecuritySchemeFactory::create(),
                    ),
                ),
                SecurityRequirement::create(
                    RequiredSecurity::create(
                        TestHTTPBearerSecuritySchemeFactory::create(),
                    ),
                    RequiredSecurity::create(
                        TestOAuth2PasswordSecuritySchemeFactory::create(),
                    ),
                ),
                SecurityRequirement::create(
                    RequiredSecurity::create(
                        TestHTTPBearerSecuritySchemeFactory::create(),
                    ),
                ),
                SecurityRequirement::create(
                    RequiredSecurity::create(
                        TestHTTPBearerSecuritySchemeFactory::create(),
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
            //            [
            //                TestHTTPBearerSecuritySchemeFactory::create(),
            //                [
            //                    TestHTTPBearerSecuritySchemeFactory::create(),
            //                    TestHTTPBearerSecuritySchemeFactory::create(),
            //                ],
            //                [
            //                    TestHTTPBearerSecuritySchemeFactory::create(),
            //                ],
            //                TestHTTPBearerSecuritySchemeFactory::create(),
            //                [
            //                    TestApiKeySecuritySchemeFactory::create(),
            //                ],
            //                TestApiKeySecuritySchemeFactory::create(),
            //            ],
        ],
    ]);

    it('can buildup the security scheme', function (): void {
        $components = Components::create()
            ->securitySchemes(TestHTTPBearerSecuritySchemeFactory::create());

        $operation = Operation::get()
            ->responses(
                Responses::create(
                    ResponseEntry::create(
                        HTTPStatusCode::ok(),
                        Response::create(Description::create('OK')),
                    ),
                ),
            );

        $openApi = OpenAPI::v311(
            Info::create(
                Title::create('Example API'),
                Version::create('1.0'),
            ),
        )->security((new TestSingleHTTPBearerSchemeSecurityFactory())->object())
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
                    TestHTTPBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                ],
            ],
            'security' => [
                [
                    TestHTTPBearerSecuritySchemeFactory::name() => [],
                ],
            ],
        ];
        $this->assertSame($expected, $openApi->asArray());
    });

    it('can add operation security using builder', function (): void {
        $components = Components::create()
            ->securitySchemes(TestHTTPBearerSecuritySchemeFactory::create());

        $routeInformation = RouteInfo::create(
            Route::get('/example', static fn (): string => 'example'),
        );
        $routeInformation->actionAttributes = collect([
            new AttributesOperation(security: TestSingleHTTPBearerSchemeSecurityFactory::class),
        ]);

        $securityBuilder = app(SecurityBuilder::class);

        $operation = Operation::get()
            ->responses(
                Responses::create(
                    ResponseEntry::create(
                        HTTPStatusCode::ok(),
                        Response::create(Description::create('OK')),
                    ),
                ),
            )->security($securityBuilder->build($routeInformation->operationAttribute()->security));

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
                    'get' => [
                        'responses' => [
                            '200' => [
                                'description' => 'OK',
                            ],
                        ],
                        'security' => [
                            [
                                TestHTTPBearerSecuritySchemeFactory::name() => [],
                            ],
                        ],
                    ],
                ],
            ],
            'components' => [
                'securitySchemes' => [
                    TestHTTPBearerSecuritySchemeFactory::name() => bearerSecurityExpectations(),
                ],
            ],
        ];

        expect($openApi->asArray())->toBe($expected);
    });
})->covers(SecurityBuilder::class);
