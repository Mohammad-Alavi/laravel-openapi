<?php

use MohammadAlavi\LaravelOpenApi\Collections\ParameterCollection;
use MohammadAlavi\LaravelOpenApi\Collections\Path;
use MohammadAlavi\ObjectOrientedOpenAPI\Enums\OASVersion;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\AllOf;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocs;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenApi;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag;
use Tests\Doubles\Stubs\Petstore\Security\SecurityRequirements\ExampleSingleBearerSecurityRequirement;
use Tests\Doubles\Stubs\Petstore\Security\SecuritySchemes\ExampleHTTPBearerSecurityScheme;

describe('OpenApi', function (): void {
    it('can generate valid OpenAPI v3.1.0 docs', function (): void {
        $tag = Tag::create()
            ->name('Audits')
            ->description('All the audits');
        $contact = Contact::create()
            ->name('Example')
            ->url('https://example.com')
            ->email('hello@example.com');
        $info = Info::create()
            ->title('API Specification')
            ->version('v1')
            ->description('For using the Example App API')
            ->contact($contact);
        $schema = Schema::object('Audits Response')
            ->properties(
                Schema::string('id')->format(Schema::FORMAT_UUID),
                Schema::string('created_at')->format(Schema::FORMAT_DATE_TIME),
                Schema::integer('age')->example(60),
                Schema::array('data')->items(
                    AllOf::create('test')->schemas(
                        Schema::string('id')->format(Schema::FORMAT_UUID),
                    ),
                ),
            )->required('id', 'created_at');
        $expectedResponse = Response::ok()
            ->content(MediaType::json()->schema($schema));
        $operationIndex = Operation::get()
            ->responses(Responses::create($expectedResponse))
            ->tags($tag)
            ->summary('List all audits')
            ->operationId('audits.index');
        $operationCreate = Operation::post()
            ->responses(Responses::create($expectedResponse))
            ->tags($tag)
            ->summary('Create an audit')
            ->operationId('audits.store')
            ->requestBody(RequestBody::create()->content(MediaType::json()->schema($schema)));
        $auditId = Schema::string('audit')->format(Schema::FORMAT_UUID);
        $format = Schema::string('format')
            ->enum('json', 'ics')
            ->default('json');
        $operationGet = Operation::get()
            ->responses(Responses::create($expectedResponse))
            ->tags($tag)
            ->summary('View an audit')
            ->operationId('audits.show')
            ->parameters(
                ParameterCollection::create(
                    Parameter::path()
                        ->name('audit')
                        ->schema($auditId)
                        ->required(),
                    Parameter::query()
                        ->name('format')
                        ->schema($format)
                        ->description('The format of the appointments'),
                ),
            );
        $paths = Paths::create(
            Path::create(
                '/audits',
                PathItem::create()
                    ->operations($operationIndex, $operationCreate),
            ),
            Path::create(
                '/audits/{audit}',
                PathItem::create()
                    ->operations($operationGet),
            ),
        );
        $servers = [
            Server::create()->url('https://api.example.com/v1'),
            Server::create()->url('https://api.example.com/v2'),
        ];
        $components = Components::create()->securitySchemes(ExampleHTTPBearerSecurityScheme::create());
        $security = Security::create(ExampleSingleBearerSecurityRequirement::create());
        $externalDocs = ExternalDocs::create()
            ->url('https://example.com')
            ->description('Example');
        $openApi = OpenApi::create()
            ->openapi(OASVersion::V_3_1_0)
            ->info($info)
            ->paths($paths)
            ->servers(...$servers)
            ->components($components)
            ->security($security)
            ->tags($tag)
            ->externalDocs($externalDocs);

        $data = $openApi->asArray();
        $result = file_put_contents('openapi.json', $openApi->toJson());
        // docker run --rm -v $PWD:/spec redocly/cli lint --extends recommend openapi.json
    });
    // TODO: move and use these to test the Security class
    //    ->with([
    //        function (): SecurityScheme {
    //            return OAuth2::create(
    //                Flows::create()
    //                    ->implicit(
    //                        Flows\Implicit::create(
    //                            'https://api.example.com/oauth/authorize',
    //                            'https://api.example.com/oauth/refresh',
    //                            ScopeCollection::create(
    //                                Scope::create('read:audits', 'Read audits'),
    //                                Scope::create('write:audits', 'Write audits'),
    //                            ),
    //                        ),
    //                    ),
    //            );
    //        },
    //        function (): SecurityScheme {
    //            return OAuth2::create(
    //                Flows::create()
    //                    ->password(
    //                        Flows\Password::create(
    //                            'https://api.example.com/oauth/authorize',
    //                            'https://api.example.com/oauth/refresh',
    //                            ScopeCollection::create(
    //                                Scope::create('read:audits', 'Read audits'),
    //                                Scope::create('write:audits', 'Write audits'),
    //                            ),
    //                        ),
    //                    ),
    //            );
    //        },
    //        function (): SecurityScheme {
    //            return OAuth2::create(
    //                Flows::create()
    //                    ->clientCredentials(
    //                        Flows\ClientCredentials::create(
    //                            'https://api.example.com/oauth/authorize',
    //                            'https://api.example.com/oauth/refresh',
    //                            ScopeCollection::create(
    //                                Scope::create('read:audits', 'Read audits'),
    //                                Scope::create('write:audits', 'Write audits'),
    //                            ),
    //                        ),
    //                    ),
    //            );
    //        },
    //        function (): SecurityScheme {
    //            return OAuth2::create(
    //                Flows::create()
    //                    ->authorizationCode(
    //                        Flows\AuthorizationCode::create(
    //                            'https://api.example.com/oauth/authorize',
    //                            'https://api.example.com/oauth/token',
    //                            'https://api.example.com/oauth/refresh',
    //                            ScopeCollection::create(
    //                                Scope::create('read:audits', 'Read audits'),
    //                                Scope::create('write:audits', 'Write audits'),
    //                            ),
    //                        ),
    //                    ),
    //            );
    //        },
    //        fn (): SecurityScheme => ApiKey::create('X-API-Key', ApiKeyLocation::HEADER),
    //        fn (): SecurityScheme => ApiKey::create('in-query', ApiKeyLocation::QUERY),
    //        fn (): SecurityScheme => ApiKey::create('in-cookie', ApiKeyLocation::COOKIE),
    //        fn (): SecurityScheme => Http::basic('test_api_key'),
    //        fn (): SecurityScheme => Http::bearer('test_api_key', 'JWT'),
    //        fn (): SecurityScheme => OpenIdConnect::create('https://api.example.com/.well-known/openid-configuration'),
    //    ]);

    // TODO: write test
    //    it('can be created using security method', function (Security $security, array $expectation): void {
    //        $openApi = OpenApi::create()->security($security);
    //
    //        $result = $openApi->asArray();
    //
    //        expect($result)->toBe($expectation);
    //    })->with([
    //        'empty array [] security' => [
    //            [],
    //            ['openapi' => OASVersion::V_3_1_0->value],
    //        ],
    //        'no security' => [
    //            (new ExampleNoSecurityRequirementSecurity())->build(),
    //            [
    //                'openapi' => OASVersion::V_3_1_0->value,
    //                'security' => [
    //                    [],
    //                ],
    //            ],
    //        ],
    //        'one element array security' => [
    //            [(new SecurityRequirementBuilder())->build(ASecuritySchemeFactory::class)],
    //            [
    //                'openapi' => OASVersion::V_3_1_0->value,
    //                'security' => [
    //                    [
    //                        'ASecuritySchemeFactory' => [],
    //                    ],
    //                ],
    //            ],
    //        ],
    //        'nested security' => [
    //            [
    //                (new SecurityRequirementBuilder())->build([
    //                    ASecuritySchemeFactory::class,
    //                    BSecuritySchemeFactory::class,
    //                ]),
    //            ],
    //            [
    //                'openapi' => OASVersion::V_3_1_0->value,
    //                'security' => [
    //                    [
    //                        'ASecuritySchemeFactory' => [],
    //                    ],
    //                    [
    //                        'BSecuritySchemeFactory' => [],
    //                    ],
    //                ],
    //            ],
    //        ],
    //        'multiple nested security' => [
    //            [
    //                (new SecurityRequirementBuilder())->build([
    //                    BSecuritySchemeFactory::class,
    //                ]),
    //                (new SecurityRequirementBuilder())->build([
    //                    ASecuritySchemeFactory::class,
    //                    BSecuritySchemeFactory::class,
    //                ]),
    //            ],
    //            [
    //                'openapi' => OASVersion::V_3_1_0->value,
    //                'security' => [
    //                    [
    //                        'BSecuritySchemeFactory' => [],
    //                    ],
    //                ],
    //            ],
    //        ],
    //    ])->skip();
})->coversNothing();
