<?php

namespace Tests\Unit\Collectors\Paths\Operations;

use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation as AttributesOperation;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\Operation\SecurityBuilder;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder;
use MohammadAlavi\LaravelOpenApi\Collections\Path;
use MohammadAlavi\LaravelOpenApi\Objects\RouteInfo;
use MohammadAlavi\ObjectOrientedOpenAPI\Enums\Version;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Components;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Title;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\OpenAPI;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use Tests\Doubles\Stubs\Petstore\Security\ExampleSingleSecurityRequirementSecurity;
use Tests\Doubles\Stubs\Petstore\Security\SecuritySchemes\ExampleApiKeySecurityScheme;
use Tests\Doubles\Stubs\Petstore\Security\SecuritySchemes\ExampleHTTPBearerSecurityScheme;

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

    /** @return string[] */
    function oAuth2SecurityExpectations(): array
    {
        return [
            'type' => 'http',
            'description' => 'Example Bearer Security',
            'scheme' => 'ExampleHTTPBearerSecurityScheme',
        ];
    }

    it('can apply multiple security schemes on operation', function (
        array $expectations,
        array $securitySchemeFactories,
        array $globalSecurity,
        string|array|null $pathSecurity,
    ): void {
        $components = Components::create()->securitySchemes(...$securitySchemeFactories);

        $route = '/foo';
        $action = 'get';
        $routeInformation = RouteInfo::create(
            Route::$action($route, static fn (): string => 'example'),
        );
        $routeInformation->actionAttributes = collect([
            new AttributesOperation(security: $pathSecurity),
        ]);
        $operation = app(OperationBuilder::class)->build($routeInformation);

        $openApi = OpenAPI::v311(
            Info::create(
                Title::create('Example API'),
                \MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Version::create('1.0'),
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
            'openapi' => Version::V_3_1_0->value,
            'paths' => [
                $route => $actionData,
            ], ...$collectionData,
        ], $openApi->asArray());
    })->with(
        [
            'No global security - no path security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'ExampleHTTPBearerSecurityScheme' => bearerSecurityExpectations(),
                            'ExampleApiKeySecurityScheme' => apiKeySecurityExpectations(),
                            'ExampleOAuth2PasswordSecurityScheme' => oAuth2SecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => null,
                    'pathSecurity' => null,
                ],
                [ // available global securities (components)
                    ExampleHTTPBearerSecurityScheme::create(),
                    ExampleApiKeySecurityScheme::create(),
                    ExampleHTTPBearerSecurityScheme::create(),
                ],
                null, // applied global security
                null, // use default global securities
            ],
            'Use default global security - have single class string security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'ExampleApiKeySecurityScheme' => apiKeySecurityExpectations(),
                            'ExampleHTTPBearerSecurityScheme' => bearerSecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'ExampleApiKeySecurityScheme' => [],
                        ],
                    ],
                    'pathSecurity' => null,
                ],
                [
                    ExampleApiKeySecurityScheme::create(),
                    ExampleHTTPBearerSecurityScheme::create(),
                ],
                [
                    ExampleApiKeySecurityScheme::create(),
                ],
                null,
            ],
            'Use default global security - have multi-auth security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'ExampleApiKeySecurityScheme' => apiKeySecurityExpectations(),
                            'ExampleOAuth2PasswordSecurityScheme' => oAuth2SecurityExpectations(),
                            'ExampleHTTPBearerSecurityScheme' => bearerSecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'ExampleApiKeySecurityScheme' => [],
                        ],
                        [
                            'ExampleApiKeySecurityScheme' => [],
                            'ExampleHTTPBearerSecurityScheme' => [],
                        ],
                        [
                            'ExampleHTTPBearerSecurityScheme' => [],
                        ],
                        [
                            'ExampleHTTPBearerSecurityScheme' => [],
                            'ExampleApiKeySecurityScheme' => [],
                        ],
                        [
                            'ExampleHTTPBearerSecurityScheme' => [],
                            'ExampleApiKeySecurityScheme' => [],
                            'ExampleOAuth2PasswordSecurityScheme' => [],
                        ],
                        [
                            'ExampleApiKeySecurityScheme' => [],
                        ],
                    ],
                    'pathSecurity' => null,
                ],
                [
                    ExampleApiKeySecurityScheme::create(),
                    ExampleHTTPBearerSecurityScheme::create(),
                    ExampleHTTPBearerSecurityScheme::create(),
                ],
                [
                    ExampleApiKeySecurityScheme::create(),
                    [
                        ExampleApiKeySecurityScheme::create(),
                        ExampleHTTPBearerSecurityScheme::create(),
                    ],
                    ExampleHTTPBearerSecurityScheme::create(),
                    [
                        ExampleHTTPBearerSecurityScheme::create(),
                        ExampleApiKeySecurityScheme::create(),
                    ],
                    [
                        ExampleHTTPBearerSecurityScheme::create(),
                        ExampleApiKeySecurityScheme::create(),
                        ExampleHTTPBearerSecurityScheme::create(),
                    ],
                    [
                        // TODO: should this duplication be removed?
                        //  I don't think it is removed automatically.
                        ExampleApiKeySecurityScheme::create(),
                    ],
                ],
                null,
            ],
            'Override global security - disable global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'ExampleApiKeySecurityScheme' => apiKeySecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'ExampleApiKeySecurityScheme' => [],
                        ],
                    ],
                    'pathSecurity' => [],
                ],
                [
                    ExampleApiKeySecurityScheme::create(),
                ],
                [
                    ExampleApiKeySecurityScheme::create(),
                ],
                [],
            ],
            'Override global security - with same security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'ExampleHTTPBearerSecurityScheme' => bearerSecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'ExampleHTTPBearerSecurityScheme' => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            'ExampleHTTPBearerSecurityScheme' => [],
                        ],
                    ],
                ],
                [
                    ExampleHTTPBearerSecurityScheme::create(), // available global securities (components)
                ],
                [
                    ExampleHTTPBearerSecurityScheme::create(), // applied global securities
                ],
                ExampleHTTPBearerSecurityScheme::create(), // security overrides
            ],
            'Override global security - single auth class string' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'ExampleHTTPBearerSecurityScheme' => bearerSecurityExpectations(),
                            'ExampleApiKeySecurityScheme' => apiKeySecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'ExampleApiKeySecurityScheme' => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            'ExampleHTTPBearerSecurityScheme' => [],
                        ],
                    ],
                ],
                [
                    ExampleHTTPBearerSecurityScheme::create(),
                    ExampleApiKeySecurityScheme::create(),
                ],
                [
                    ExampleApiKeySecurityScheme::create(),
                ],
                ExampleHTTPBearerSecurityScheme::create(),
            ],
            'Override global security - single auth array' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'ExampleHTTPBearerSecurityScheme' => bearerSecurityExpectations(),
                            'ExampleApiKeySecurityScheme' => apiKeySecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'ExampleHTTPBearerSecurityScheme' => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            'ExampleApiKeySecurityScheme' => [],
                        ],
                    ],
                ],
                [
                    ExampleHTTPBearerSecurityScheme::create(),
                    ExampleApiKeySecurityScheme::create(),
                ],
                [
                    ExampleHTTPBearerSecurityScheme::create(),
                ],
                [
                    ExampleApiKeySecurityScheme::create(),
                ],
            ],
            'Override global security - multi-auth (and) - single auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'ExampleHTTPBearerSecurityScheme' => bearerSecurityExpectations(),
                            'ExampleApiKeySecurityScheme' => apiKeySecurityExpectations(),
                            'ExampleOAuth2PasswordSecurityScheme' => oAuth2SecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'ExampleHTTPBearerSecurityScheme' => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            'ExampleApiKeySecurityScheme' => [],
                            'ExampleHTTPBearerSecurityScheme' => [],
                        ],
                    ],
                ],
                [
                    ExampleHTTPBearerSecurityScheme::create(),
                    ExampleApiKeySecurityScheme::create(),
                    ExampleHTTPBearerSecurityScheme::create(),
                ],
                [
                    ExampleHTTPBearerSecurityScheme::create(),
                ],
                [
                    [
                        ExampleApiKeySecurityScheme::create(),
                        ExampleHTTPBearerSecurityScheme::create(),
                    ],
                ],
            ],
            'Override global security - multi-auth (and) - multi auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'ExampleHTTPBearerSecurityScheme' => bearerSecurityExpectations(),
                            'ExampleApiKeySecurityScheme' => apiKeySecurityExpectations(),
                            'ExampleOAuth2PasswordSecurityScheme' => oAuth2SecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'ExampleHTTPBearerSecurityScheme' => [],
                            'ExampleOAuth2PasswordSecurityScheme' => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            'ExampleHTTPBearerSecurityScheme' => [],
                            'ExampleApiKeySecurityScheme' => [],
                        ],
                    ],
                ],
                [
                    ExampleHTTPBearerSecurityScheme::create(),
                    ExampleApiKeySecurityScheme::create(),
                    ExampleHTTPBearerSecurityScheme::create(),
                ],
                [
                    [
                        ExampleHTTPBearerSecurityScheme::create(),
                        ExampleHTTPBearerSecurityScheme::create(),
                    ],
                ],
                [
                    [
                        ExampleHTTPBearerSecurityScheme::create(),
                        ExampleApiKeySecurityScheme::create(),
                    ],
                ],
            ],
            'Override global security - multi-auth (or) - single auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'ExampleHTTPBearerSecurityScheme' => bearerSecurityExpectations(),
                            'ExampleApiKeySecurityScheme' => apiKeySecurityExpectations(),
                            'ExampleOAuth2PasswordSecurityScheme' => oAuth2SecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'ExampleHTTPBearerSecurityScheme' => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            'ExampleHTTPBearerSecurityScheme' => [],
                        ],
                        [
                            'ExampleApiKeySecurityScheme' => [],
                        ],
                    ],
                ],
                [
                    ExampleHTTPBearerSecurityScheme::create(),
                    ExampleApiKeySecurityScheme::create(),
                    ExampleHTTPBearerSecurityScheme::create(),
                ],
                [
                    ExampleHTTPBearerSecurityScheme::create(),
                ],
                [
                    [
                        ExampleHTTPBearerSecurityScheme::create(),
                    ],
                    [
                        ExampleApiKeySecurityScheme::create(),
                    ],
                ],
            ],
            'Override global security - multi-auth (or) - multi auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'ExampleHTTPBearerSecurityScheme' => bearerSecurityExpectations(),
                            'ExampleApiKeySecurityScheme' => apiKeySecurityExpectations(),
                            'ExampleOAuth2PasswordSecurityScheme' => oAuth2SecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'ExampleHTTPBearerSecurityScheme' => [],
                            'ExampleApiKeySecurityScheme' => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            'ExampleHTTPBearerSecurityScheme' => [],
                        ],
                        [
                            'ExampleApiKeySecurityScheme' => [],
                        ],
                    ],
                ],
                [
                    ExampleHTTPBearerSecurityScheme::create(),
                    ExampleApiKeySecurityScheme::create(),
                    ExampleHTTPBearerSecurityScheme::create(),
                ],
                [
                    [
                        ExampleHTTPBearerSecurityScheme::create(),
                        ExampleApiKeySecurityScheme::create(),
                    ],
                ],
                [
                    [
                        ExampleHTTPBearerSecurityScheme::create(),
                    ],
                    [
                        ExampleApiKeySecurityScheme::create(),
                    ],
                ],
            ],
            'Override global security - multi-auth (and + or) - single auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'ExampleOAuth2PasswordSecurityScheme' => oAuth2SecurityExpectations(),
                            'ExampleHTTPBearerSecurityScheme' => bearerSecurityExpectations(),
                            'ExampleApiKeySecurityScheme' => apiKeySecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'ExampleHTTPBearerSecurityScheme' => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            'ExampleApiKeySecurityScheme' => [],
                        ],
                        [
                            'ExampleHTTPBearerSecurityScheme' => [],
                            'ExampleOAuth2PasswordSecurityScheme' => [],
                        ],
                    ],
                ],
                [
                    ExampleHTTPBearerSecurityScheme::create(),
                    ExampleHTTPBearerSecurityScheme::create(),
                    ExampleApiKeySecurityScheme::create(),
                ],
                [
                    ExampleHTTPBearerSecurityScheme::create(),
                ],
                [
                    ExampleApiKeySecurityScheme::create(),
                    [
                        ExampleHTTPBearerSecurityScheme::create(),
                        ExampleHTTPBearerSecurityScheme::create(),
                    ],
                ],
            ],
            'Override global security - multi-auth (and + or) - multi auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'ExampleOAuth2PasswordSecurityScheme' => oAuth2SecurityExpectations(),
                            'ExampleHTTPBearerSecurityScheme' => bearerSecurityExpectations(),
                            'ExampleApiKeySecurityScheme' => apiKeySecurityExpectations(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'ExampleHTTPBearerSecurityScheme' => [],
                            'ExampleApiKeySecurityScheme' => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            'ExampleHTTPBearerSecurityScheme' => [],
                        ],
                        [
                            'ExampleHTTPBearerSecurityScheme' => [],
                            'ExampleOAuth2PasswordSecurityScheme' => [],
                        ],
                        [
                            'ExampleApiKeySecurityScheme' => [],
                        ],
                    ],
                ],
                [
                    ExampleHTTPBearerSecurityScheme::create(),
                    ExampleHTTPBearerSecurityScheme::create(),
                    ExampleApiKeySecurityScheme::create(),
                ],
                [
                    [
                        ExampleHTTPBearerSecurityScheme::create(),
                        ExampleApiKeySecurityScheme::create(),
                    ],
                ],
                [
                    [
                        ExampleHTTPBearerSecurityScheme::create(),
                    ],
                    [
                        ExampleHTTPBearerSecurityScheme::create(),
                        ExampleHTTPBearerSecurityScheme::create(),
                    ],
                    [
                        ExampleApiKeySecurityScheme::create(),
                    ],
                ],
            ],
        ],
    );

    it('can apply multiple security schemes globally', function (
        array $expectedJson,
        array $securitySchemeFactories,
        array $globalSecurity,
    ): void {
        $components = Components::create()->securitySchemes(...$securitySchemeFactories);

        $operation = Operation::create()
            ->action('get');

        $openApi = OpenAPI::v311(
            Info::create(
                Title::create('Example API'),
                \MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Version::create('1.0'),
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

        // Assert that the generated JSON matches the expected JSON for this scenario
        $expected = [
            'openapi' => Version::V_3_1_0->value,
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
    })->with([
        // JWT authentication only
        [
            [
                'components' => [
                    'securitySchemes' => [
                        'ExampleHTTPBearerSecurityScheme' => bearerSecurityExpectations(),
                    ],
                ],
                'security' => [
                    [
                        'ExampleHTTPBearerSecurityScheme' => [],
                    ],
                ],
            ],
            [
                ExampleHTTPBearerSecurityScheme::create(),
            ],
            [
                [
                    ExampleHTTPBearerSecurityScheme::create(),
                ],
            ],
        ],
        // ApiKey authentication only
        [
            [
                'components' => [
                    'securitySchemes' => [
                        'ExampleApiKeySecurityScheme' => apiKeySecurityExpectations(),
                    ],
                ],
                'security' => [
                    [
                        'ExampleApiKeySecurityScheme' => [],
                    ],
                ],
            ],
            [
                ExampleApiKeySecurityScheme::create(),
            ],
            [
                ExampleApiKeySecurityScheme::create(),
            ],
        ],
        // Both JWT and ApiKey authentication required
        [
            [
                'components' => [
                    'securitySchemes' => [
                        'ExampleHTTPBearerSecurityScheme' => bearerSecurityExpectations(),
                        'ExampleApiKeySecurityScheme' => apiKeySecurityExpectations(),
                    ],
                ],
                'security' => [
                    [
                        'ExampleHTTPBearerSecurityScheme' => [],
                        'ExampleApiKeySecurityScheme' => [],
                    ],
                ],
            ],
            [
                ExampleHTTPBearerSecurityScheme::create(),
                ExampleApiKeySecurityScheme::create(),
            ],
            [
                [
                    ExampleHTTPBearerSecurityScheme::create(),
                    ExampleApiKeySecurityScheme::create(),
                ],
            ],
        ],
        // Either JWT or ApiKey authentication required
        [
            [
                'components' => [
                    'securitySchemes' => [
                        'ExampleHTTPBearerSecurityScheme' => bearerSecurityExpectations(),
                        'ExampleApiKeySecurityScheme' => apiKeySecurityExpectations(),
                    ],
                ],
                'security' => [
                    [
                        'ExampleHTTPBearerSecurityScheme' => [],
                    ],
                    [
                        'ExampleApiKeySecurityScheme' => [],
                    ],
                ],
            ],
            [
                ExampleHTTPBearerSecurityScheme::create(),
                ExampleApiKeySecurityScheme::create(),
            ],
            [
                [
                    ExampleHTTPBearerSecurityScheme::create(),
                ],
                [
                    ExampleApiKeySecurityScheme::create(),
                ],
            ],
        ],
        // And & Or combination
        [
            [
                'components' => [
                    'securitySchemes' => [
                        'ExampleHTTPBearerSecurityScheme' => bearerSecurityExpectations(),
                        'ExampleApiKeySecurityScheme' => apiKeySecurityExpectations(),
                        'ExampleOAuth2PasswordSecurityScheme' => oAuth2SecurityExpectations(),
                    ],
                ],
                'security' => [
                    [
                        'ExampleHTTPBearerSecurityScheme' => [],
                    ],
                    [
                        'ExampleHTTPBearerSecurityScheme' => [],
                        'ExampleOAuth2PasswordSecurityScheme' => [],
                    ],
                    [
                        'ExampleHTTPBearerSecurityScheme' => [],
                    ],
                    [
                        'ExampleHTTPBearerSecurityScheme' => [],
                    ],
                    [
                        'ExampleApiKeySecurityScheme' => [],
                    ],
                    [
                        'ExampleApiKeySecurityScheme' => [],
                    ],
                ],
            ],
            [
                ExampleHTTPBearerSecurityScheme::create(),
                ExampleApiKeySecurityScheme::create(),
                ExampleHTTPBearerSecurityScheme::create(),
            ],
            [
                ExampleHTTPBearerSecurityScheme::create(),
                [
                    ExampleHTTPBearerSecurityScheme::create(),
                    ExampleHTTPBearerSecurityScheme::create(),
                ],
                [
                    ExampleHTTPBearerSecurityScheme::create(),
                ],
                ExampleHTTPBearerSecurityScheme::create(),
                [
                    ExampleApiKeySecurityScheme::create(),
                ],
                ExampleApiKeySecurityScheme::create(),
            ],
        ],
    ]);

    it('can buildup the security scheme', function (): void {
        $components = Components::create()
            ->securitySchemes(ExampleHTTPBearerSecurityScheme::create());

        $operation = Operation::get()
            ->responses(
                Responses::create(
                    Response::ok(),
                ),
            );

        $openApi = OpenAPI::v311(
            Info::create(
                Title::create('Example API'),
                \MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Version::create('1.0'),
            ),
        )->security((new ExampleSingleSecurityRequirementSecurity())->build())
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
            'openapi' => Version::V_3_1_0->value,
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
                    'ExampleHTTPBearerSecurityScheme' => bearerSecurityExpectations(),
                ],
            ],
            'security' => [
                [
                    'ExampleHTTPBearerSecurityScheme' => [],
                ],
            ],
        ];
        $this->assertSame($expected, $openApi->asArray());
    });

    it('can add operation security using builder', function (): void {
        $components = Components::create()
            ->securitySchemes(ExampleHTTPBearerSecurityScheme::create());

        $routeInformation = RouteInfo::create(
            Route::get('/example', static fn (): string => 'example'),
        );
        $routeInformation->actionAttributes = collect([
            new AttributesOperation(security: ExampleSingleSecurityRequirementSecurity::class),
        ]);

        $securityBuilder = app(SecurityBuilder::class);

        $operation = Operation::get()
            ->responses(
                Responses::create(
                    Response::ok(),
                ),
            )
            ->security($securityBuilder->build($routeInformation->operationAttribute()->security));

        $openApi = OpenAPI::v311(
            Info::create(
                Title::create('Example API'),
                \MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Version::create('1.0'),
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
            'openapi' => Version::V_3_1_0->value,
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
                                'ExampleHTTPBearerSecurityScheme' => [],
                            ],
                        ],
                    ],
                ],
            ],
            'components' => [
                'securitySchemes' => [
                    'ExampleHTTPBearerSecurityScheme' => bearerSecurityExpectations(),
                ],
            ],
        ];

        expect($openApi->asArray())->toBe($expected);
    });
})->covers(SecurityBuilder::class)->skip();
