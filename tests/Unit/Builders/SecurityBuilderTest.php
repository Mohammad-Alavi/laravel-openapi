<?php

namespace Tests\Unit\Builders;

use MohammadAlavi\ObjectOrientedOAS\Objects\Components;
use MohammadAlavi\ObjectOrientedOAS\Objects\PathItem;
use MohammadAlavi\ObjectOrientedOAS\Objects\SecurityScheme;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation as AttributesOperation;
use MohammadAlavi\LaravelOpenApi\Collectors\Paths\Operation\SecurityRequirementBuilder as OperationSecurityBuilder;
use MohammadAlavi\LaravelOpenApi\Collectors\Paths\OperationBuilder;
use MohammadAlavi\LaravelOpenApi\Factories\Component\SecuritySchemeFactory;
use MohammadAlavi\LaravelOpenApi\Objects\OpenApi;
use MohammadAlavi\LaravelOpenApi\Objects\Operation;
use MohammadAlavi\LaravelOpenApi\Objects\RouteInformation;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

#[CoversClass(OperationSecurityBuilder::class)]
class SecurityBuilderTest extends TestCase
{
    public static function operationSecuritySchemesDataProvider(): array
    {
        return [
            'No global security - no path security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'JWT' => self::jwtSecuritySchemeProvider(),
                            'ApiKey' => self::apiKeyAuthSecuritySchemeProvider(),
                            'Bearer' => self::bearerAuthSecuritySchemeProvider(),
                        ],
                    ],
                    'globalSecurity' => null,
                    'pathSecurity' => null,
                ],
                [ // available global securities (components)
                    JwtSecurityScheme::class,
                    ApiKeySecurityScheme::class,
                    BearerSecurityScheme::class,
                ],
                [], // applied global security
                null, // use default global securities
            ],
            'Use default global security - have single class string security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'ApiKey' => self::apiKeyAuthSecuritySchemeProvider(),
                            'JWT' => self::jwtSecuritySchemeProvider(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'ApiKey' => [],
                        ],
                    ],
                    'pathSecurity' => null,
                ],
                [
                    ApiKeySecurityScheme::class,
                    JwtSecurityScheme::class,
                ],
                [
                    ApiKeySecurityScheme::class,
                ],
                null,
            ],
            'Use default global security - have multi-auth security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'ApiKey' => self::apiKeyAuthSecuritySchemeProvider(),
                            'Bearer' => self::bearerAuthSecuritySchemeProvider(),
                            'JWT' => self::jwtSecuritySchemeProvider(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'ApiKey' => [],
                        ],
                        [
                            'ApiKey' => [],
                            'JWT' => [],
                        ],
                        [
                            'Bearer' => [],
                        ],
                        [
                            'Bearer' => [],
                            'ApiKey' => [],
                        ],
                        [
                            'Bearer' => [],
                            'ApiKey' => [],
                            'JWT' => [],
                        ],
                        [
                            'ApiKey' => [],
                        ],
                    ],
                    'pathSecurity' => null,
                ],
                [
                    ApiKeySecurityScheme::class,
                    BearerSecurityScheme::class,
                    JwtSecurityScheme::class,
                ],
                [
                    ApiKeySecurityScheme::class,
                    [
                        ApiKeySecurityScheme::class,
                        JwtSecurityScheme::class,
                    ],
                    BearerSecurityScheme::class,
                    [
                        BearerSecurityScheme::class,
                        ApiKeySecurityScheme::class,
                    ],
                    [
                        BearerSecurityScheme::class,
                        ApiKeySecurityScheme::class,
                        JwtSecurityScheme::class,
                    ],
                    [
                        // TODO: should this duplication be removed?
                        //  I don't think it is removed automatically.
                        ApiKeySecurityScheme::class,
                    ],
                ],
                null,
            ],
            'Override global security - disable global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'ApiKey' => self::apiKeyAuthSecuritySchemeProvider(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'ApiKey' => [],
                        ],
                    ],
                    'pathSecurity' => [],
                ],
                [
                    ApiKeySecurityScheme::class,
                ],
                [
                    ApiKeySecurityScheme::class,
                ],
                [],
            ],
            'Override global security - with same security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'JWT' => self::jwtSecuritySchemeProvider(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'JWT' => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            'JWT' => [],
                        ],
                    ],
                ],
                [
                    JwtSecurityScheme::class, // available global securities (components)
                ],
                [
                    JwtSecurityScheme::class, // applied global securities
                ],
                JwtSecurityScheme::class, // security overrides
            ],
            'Override global security - single auth class string' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'JWT' => self::jwtSecuritySchemeProvider(),
                            'ApiKey' => self::apiKeyAuthSecuritySchemeProvider(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'ApiKey' => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            'JWT' => [],
                        ],
                    ],
                ],
                [
                    JwtSecurityScheme::class,
                    ApiKeySecurityScheme::class,
                ],
                [
                    ApiKeySecurityScheme::class,
                ],
                JwtSecurityScheme::class,
            ],
            'Override global security - single auth array' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'JWT' => self::jwtSecuritySchemeProvider(),
                            'ApiKey' => self::apiKeyAuthSecuritySchemeProvider(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'JWT' => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            'ApiKey' => [],
                        ],
                    ],
                ],
                [
                    JwtSecurityScheme::class,
                    ApiKeySecurityScheme::class,
                ],
                [
                    JwtSecurityScheme::class,
                ],
                [
                    ApiKeySecurityScheme::class,
                ],
            ],
            'Override global security - multi-auth (and) - single auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'JWT' => self::jwtSecuritySchemeProvider(),
                            'ApiKey' => self::apiKeyAuthSecuritySchemeProvider(),
                            'Bearer' => self::bearerAuthSecuritySchemeProvider(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'JWT' => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            'ApiKey' => [],
                            'Bearer' => [],
                        ],
                    ],
                ],
                [
                    JwtSecurityScheme::class,
                    ApiKeySecurityScheme::class,
                    BearerSecurityScheme::class,
                ],
                [
                    JwtSecurityScheme::class,
                ],
                [
                    [
                        ApiKeySecurityScheme::class,
                        BearerSecurityScheme::class,
                    ],
                ],
            ],
            'Override global security - multi-auth (and) - multi auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'JWT' => self::jwtSecuritySchemeProvider(),
                            'ApiKey' => self::apiKeyAuthSecuritySchemeProvider(),
                            'Bearer' => self::bearerAuthSecuritySchemeProvider(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'Bearer' => [],
                            'JWT' => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            'JWT' => [],
                            'ApiKey' => [],
                        ],
                    ],
                ],
                [
                    JwtSecurityScheme::class,
                    ApiKeySecurityScheme::class,
                    BearerSecurityScheme::class,
                ],
                [
                    [
                        BearerSecurityScheme::class,
                        JwtSecurityScheme::class,
                    ],
                ],
                [
                    [
                        JwtSecurityScheme::class,
                        ApiKeySecurityScheme::class,
                    ],
                ],
            ],
            'Override global security - multi-auth (or) - single auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'JWT' => self::jwtSecuritySchemeProvider(),
                            'ApiKey' => self::apiKeyAuthSecuritySchemeProvider(),
                            'Bearer' => self::bearerAuthSecuritySchemeProvider(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'Bearer' => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            'JWT' => [],
                        ],
                        [
                            'ApiKey' => [],
                        ],
                    ],
                ],
                [
                    JwtSecurityScheme::class,
                    ApiKeySecurityScheme::class,
                    BearerSecurityScheme::class,
                ],
                [
                    BearerSecurityScheme::class,
                ],
                [
                    [
                        JwtSecurityScheme::class,
                    ],
                    [
                        ApiKeySecurityScheme::class,
                    ],
                ],
            ],
            'Override global security - multi-auth (or) - multi auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'JWT' => self::jwtSecuritySchemeProvider(),
                            'ApiKey' => self::apiKeyAuthSecuritySchemeProvider(),
                            'Bearer' => self::bearerAuthSecuritySchemeProvider(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'Bearer' => [],
                            'ApiKey' => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            'JWT' => [],
                        ],
                        [
                            'ApiKey' => [],
                        ],
                    ],
                ],
                [
                    JwtSecurityScheme::class,
                    ApiKeySecurityScheme::class,
                    BearerSecurityScheme::class,
                ],
                [
                    [
                        BearerSecurityScheme::class,
                        ApiKeySecurityScheme::class,
                    ],
                ],
                [
                    [
                        JwtSecurityScheme::class,
                    ],
                    [
                        ApiKeySecurityScheme::class,
                    ],
                ],
            ],
            'Override global security - multi-auth (and + or) - single auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'Bearer' => self::bearerAuthSecuritySchemeProvider(),
                            'JWT' => self::jwtSecuritySchemeProvider(),
                            'ApiKey' => self::apiKeyAuthSecuritySchemeProvider(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'JWT' => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            'ApiKey' => [],
                        ],
                        [
                            'JWT' => [],
                            'Bearer' => [],
                        ],
                    ],
                ],
                [
                    BearerSecurityScheme::class,
                    JwtSecurityScheme::class,
                    ApiKeySecurityScheme::class,
                ],
                [
                    JwtSecurityScheme::class,
                ],
                [
                    ApiKeySecurityScheme::class,
                    [
                        JwtSecurityScheme::class,
                        BearerSecurityScheme::class,
                    ],
                ],
            ],
            'Override global security - multi-auth (and + or) - multi auth global security' => [
                [
                    'components' => [
                        'securitySchemes' => [
                            'Bearer' => self::bearerAuthSecuritySchemeProvider(),
                            'JWT' => self::jwtSecuritySchemeProvider(),
                            'ApiKey' => self::apiKeyAuthSecuritySchemeProvider(),
                        ],
                    ],
                    'globalSecurity' => [
                        [
                            'Bearer' => [],
                            'ApiKey' => [],
                        ],
                    ],
                    'pathSecurity' => [
                        [
                            'Bearer' => [],
                        ],
                        [
                            'JWT' => [],
                            'Bearer' => [],
                        ],
                        [
                            'ApiKey' => [],
                        ],
                    ],
                ],
                [
                    BearerSecurityScheme::class,
                    JwtSecurityScheme::class,
                    ApiKeySecurityScheme::class,
                ],
                [
                    [
                        BearerSecurityScheme::class,
                        ApiKeySecurityScheme::class,
                    ],
                ],
                [
                    [
                        BearerSecurityScheme::class,
                    ],
                    [
                        JwtSecurityScheme::class,
                        BearerSecurityScheme::class,
                    ],
                    [
                        ApiKeySecurityScheme::class,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string[]
     */
    private static function jwtSecuritySchemeProvider(): array
    {
        return [
            'type' => 'http',
            'name' => 'JwtTestScheme',
            'in' => 'header',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT',
        ];
    }

    /**
     * @return string[]
     */
    private static function apiKeyAuthSecuritySchemeProvider(): array
    {
        return [
            'type' => 'apiKey',
            'name' => 'X-API-KEY',
            'in' => 'header',
            'scheme' => 'apiKey',
        ];
    }

    /**
     * @return string[]
     */
    private static function bearerAuthSecuritySchemeProvider(): array
    {
        return [
            'type' => 'http',
            'scheme' => 'bearer',
        ];
    }

    public static function globalSecuritySchemesDataProvider(): array
    {
        return [
            // JWT authentication only
            [
                [
                    'components' => [
                        'securitySchemes' => [
                            'JWT' => self::jwtSecuritySchemeProvider(),
                        ],
                    ],
                    'security' => [
                        [
                            'JWT' => [],
                        ],
                    ],
                ],
                [
                    JwtSecurityScheme::class,
                ],
                [
                    [
                        JwtSecurityScheme::class,
                    ],
                ],
            ],
            // ApiKey authentication only
            [
                [
                    'components' => [
                        'securitySchemes' => [
                            'ApiKey' => self::apiKeyAuthSecuritySchemeProvider(),
                        ],
                    ],
                    'security' => [
                        [
                            'ApiKey' => [],
                        ],
                    ],
                ],
                [
                    ApiKeySecurityScheme::class,
                ],
                [
                    ApiKeySecurityScheme::class,
                ],
            ],
            // Both JWT and ApiKey authentication required
            [
                [
                    'components' => [
                        'securitySchemes' => [
                            'JWT' => self::jwtSecuritySchemeProvider(),
                            'ApiKey' => self::apiKeyAuthSecuritySchemeProvider(),
                        ],
                    ],
                    'security' => [
                        [
                            'JWT' => [],
                            'ApiKey' => [],
                        ],
                    ],
                ],
                [
                    JwtSecurityScheme::class,
                    ApiKeySecurityScheme::class,
                ],
                [
                    [
                        JwtSecurityScheme::class,
                        ApiKeySecurityScheme::class,
                    ],
                ],
            ],
            // Either JWT or ApiKey authentication required
            [
                [
                    'components' => [
                        'securitySchemes' => [
                            'JWT' => self::jwtSecuritySchemeProvider(),
                            'ApiKey' => self::apiKeyAuthSecuritySchemeProvider(),
                        ],
                    ],
                    'security' => [
                        [
                            'JWT' => [],
                        ],
                        [
                            'ApiKey' => [],
                        ],
                    ],
                ],
                [
                    JwtSecurityScheme::class,
                    ApiKeySecurityScheme::class,
                ],
                [
                    [
                        JwtSecurityScheme::class,
                    ],
                    [
                        ApiKeySecurityScheme::class,
                    ],
                ],
            ],
            // And & Or combination
            [
                [
                    'components' => [
                        'securitySchemes' => [
                            'JWT' => self::jwtSecuritySchemeProvider(),
                            'ApiKey' => self::apiKeyAuthSecuritySchemeProvider(),
                            'Bearer' => self::bearerAuthSecuritySchemeProvider(),
                        ],
                    ],
                    'security' => [
                        [
                            'Bearer' => [],
                        ],
                        [
                            'JWT' => [],
                            'Bearer' => [],
                        ],
                        [
                            'Bearer' => [],
                        ],
                        [
                            'JWT' => [],
                        ],
                        [
                            'ApiKey' => [],
                        ],
                        [
                            'ApiKey' => [],
                        ],
                    ],
                ],
                [
                    JwtSecurityScheme::class,
                    ApiKeySecurityScheme::class,
                    BearerSecurityScheme::class,
                ],
                [
                    BearerSecurityScheme::class,
                    [
                        JwtSecurityScheme::class,
                        BearerSecurityScheme::class,
                    ],
                    [
                        BearerSecurityScheme::class,
                    ],
                    JwtSecurityScheme::class,
                    [
                        ApiKeySecurityScheme::class,
                    ],
                    ApiKeySecurityScheme::class,
                ],
            ],
        ];
    }

    #[DataProvider('operationSecuritySchemesDataProvider')]
    public function testCanApplyMultipleSecuritySchemesOnOperation(
        array             $expectedJson,
        array             $securitySchemeComponents,
        array             $globalSecurity,
        string|array|null $pathSecurity
    ): void {
        $components = Components::create()->securitySchemes(
            ...collect($securitySchemeComponents)->map(
            static fn (string $securitySchemeFactory): SecurityScheme => app($securitySchemeFactory)->build()
        )->toArray()
        );

        $action = 'get';
        $route = '/foo';
        $routeInfo = app(RouteInformation::class);
        $routeInfo->parameters = collect();
        $routeInfo->method = $action;
        $routeInfo->action = $action;
        $routeInfo->name = 'test route';
        $routeInfo->actionAttributes = collect([
            new AttributesOperation(security: $pathSecurity),
        ]);
        $routeInfo->uri = '/example';
        $operation = app(OperationBuilder::class)->build([$routeInfo])[0];
        $openApi = OpenApi::create();

        $openApi = $openApi
            ->components($components)
            ->multiAuthSecurity($globalSecurity)
            ->paths(
                PathItem::create()
                    ->route($route)
                    ->operations($operation)
            );

        // Assert that the generated JSON matches the expected JSON for this scenario
        $actionData = [
            $action => [],
        ];
        if (!is_null($expectedJson['pathSecurity'])) {
            $actionData[$action] = ['security' => $expectedJson['pathSecurity']];
        }
        $collectionData = [
            'components' => $expectedJson['components'],
        ];
        if (!is_null($expectedJson['globalSecurity'])) {
            $collectionData['security'] = $expectedJson['globalSecurity'];
        }

        self::assertSame([
            'paths' => [
                $route => $actionData,
            ], ...$collectionData,
        ], $openApi->toArray());
    }

    #[DataProvider('globalSecuritySchemesDataProvider')]
    public function testCanApplyMultipleSecuritySchemesGlobally(
        array $expectedJson,
        array $securitySchemeComponents,
        array $globalSecurity,
    ): void {
        $components = Components::create()->securitySchemes(
            ...collect($securitySchemeComponents)->map(
            static fn (string $securitySchemeFactory): SecurityScheme => app($securitySchemeFactory)->build()
        )->toArray()
        );

        $operation = Operation::create()
            ->action('get');

        $openApi = OpenApi::create()
            ->multiAuthSecurity($globalSecurity)
            ->components($components)
            ->paths(
                PathItem::create()
                    ->route('/foo')
                    ->operations($operation)
            );

        // Assert that the generated JSON matches the expected JSON for this scenario
        $expected = [
            'paths' => [
                '/foo' => [
                    'get' => [
                    ],
                ],
            ],
            'components' => $expectedJson['components'],
            'security' => $expectedJson['security'],
        ];
        self::assertSame($expected, $openApi->toArray());
        self::assertSame(json_encode($expected, JSON_THROW_ON_ERROR), $openApi->toJson());
    }

    /**
     * We're just making sure we're getting the expected output.
     */
    public function testCanBuildUpTheSecurityScheme(): void
    {
        $securityFactory = app(JwtSecurityScheme::class);
        $testJwtScheme = $securityFactory->build();

        $components = Components::create()
            ->securitySchemes($testJwtScheme);

        $operation = Operation::create()
            ->action('get');

        $openApi = OpenApi::create()
            ->multiAuthSecurity([JwtSecurityScheme::class])
            ->components($components)
            ->paths(
                PathItem::create()
                    ->route('/foo')
                    ->operations($operation)
            );

        $expected = [
            'paths' => [
                '/foo' => [
                    'get' => [],
                ],
            ],
            'components' => [
                'securitySchemes' => [
                    'JWT' => self::jwtSecuritySchemeProvider(),
                ],
            ],
            'security' => [
                [
                    'JWT' => [],
                ],
            ],
        ];
        self::assertSame($expected, $openApi->toArray());
        self::assertSame(json_encode($expected, JSON_THROW_ON_ERROR), $openApi->toJson());
    }

    /**
     * We're just verifying that the builder is capable of
     * adding security information to the operation.
     */
    public function testWeCanAddOperationSecurityUsingBuilder(): void
    {
        $securityFactory = app(JwtSecurityScheme::class);
        $testJwtScheme = $securityFactory->build();

        $components = Components::create()
            ->securitySchemes($testJwtScheme);

        $routeInfo = new RouteInformation();
        $routeInfo->action = 'get';
        $routeInfo->name = 'test route';
        $routeInfo->actionAttributes = collect([
            new AttributesOperation(security: JwtSecurityScheme::class),
        ]);
        $routeInfo->uri = '/example';

        /** @var $builder OperationSecurityBuilder */
        $builder = app(OperationSecurityBuilder::class);

        $operation = Operation::create()
            ->security($builder->build($routeInfo->actionAttributes[0]->security))
            ->action('get');

        $openApi = OpenApi::create()
            ->components($components)
            ->paths(
                PathItem::create()
                    ->route('/foo')
                    ->operations($operation)
            );

        $expected = [
            'paths' => [
                '/foo' => [
                    'get' => [
                        'security' => [
                            [
                                'JWT' => [],
                            ],
                        ],
                    ],
                ],
            ],
            'components' => [
                'securitySchemes' => [
                    'JWT' => self::jwtSecuritySchemeProvider(),
                ],
            ],
        ];
        self::assertSame($expected, $openApi->toArray());
        self::assertSame(json_encode($expected, JSON_THROW_ON_ERROR), $openApi->toJson());
    }
}

class JwtSecurityScheme extends SecuritySchemeFactory
{
    public function build(): SecurityScheme
    {
        return SecurityScheme::create('JWT')
            ->name('JwtTestScheme')
            ->type(SecurityScheme::TYPE_HTTP)
            ->in(SecurityScheme::IN_HEADER)
            ->scheme('bearer')
            ->bearerFormat('JWT');
    }
}

class ApiKeySecurityScheme extends SecuritySchemeFactory
{
    public function build(): SecurityScheme
    {
        return SecurityScheme::create('ApiKey')
            ->name('X-API-KEY')
            ->type(SecurityScheme::TYPE_API_KEY)
            ->in(SecurityScheme::IN_HEADER)
            ->scheme('apiKey');
    }
}

class BearerSecurityScheme extends SecuritySchemeFactory
{
    public function build(): SecurityScheme
    {
        return SecurityScheme::create('Bearer')
            ->type(SecurityScheme::TYPE_HTTP)
            ->scheme('bearer');
    }
}
