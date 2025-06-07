<?php

use MohammadAlavi\LaravelOpenApi\Collections\ParameterCollection;
use MohammadAlavi\LaravelOpenApi\Collections\Path;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\AllOf;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Components;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Contact;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Fields\Email;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Fields\URL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocs;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Description as InfoDescription;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Title;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Version;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\OpenAPI;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Name as ParamName;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedPath;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedQuery;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\URL as ServerURL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Description as TagDescription;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Name as TagName;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;
use Tests\Doubles\Stubs\Petstore\Security\SecurityRequirements\ExampleSingleBearerSecurityRequirement;
use Tests\Doubles\Stubs\Petstore\Security\SecuritySchemes\ExampleHTTPBearerSecurityScheme;

describe('OpenApi', function (): void {
    it('can generate valid OpenAPI v3.1.0 docs', function (): void {
        $tag = Tag::create(
            TagName::create('Audits'),
            TagDescription::create('All the audits')
        );
        $contact = Contact::create()
            ->name(Name::create('Example'))
            ->url(URL::create('https://example.com'))
            ->email(Email::create('hello@example.com'));
        $info = Info::create(
            Title::create('API Specification'),
            Version::create('v1'),
        )->description(InfoDescription::create('For using the Example App API'))
            ->contact($contact);
        $objectDescriptor = Schema::object()
            ->properties(
                Property::create('id', Schema::string()->format(StringFormat::UUID)),
                Property::create('created_at', Schema::string()->format(StringFormat::DATE_TIME)),
                Property::create('age', Schema::integer()->examples(60)),
                Property::create(
                    'data',
                    Schema::array()->items(
                        AllOf::create('test')->schemas(
                            Schema::string()->format(StringFormat::UUID),
                        ),
                    ),
                ),
            )->required('id', 'created_at');
        $expectedResponse = Response::ok()
            ->content(MediaType::json()->schema($objectDescriptor));
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
            ->requestBody(RequestBody::create()->content(MediaType::json()->schema($objectDescriptor)));
        $stringDescriptor = Schema::string()->format(StringFormat::UUID);
        $enumDescriptor = Schema::enum('json', 'ics')
            ->default('json');
        $operationGet = Operation::get()
            ->responses(Responses::create($expectedResponse))
            ->tags($tag)
            ->summary('View an audit')
            ->operationId('audits.show')
            ->parameters(
                ParameterCollection::create(
                    Parameter::path(
                        ParamName::create('audit'),
                        SchemaSerializedPath::create($stringDescriptor),
                    )->required(),
                    Parameter::query(
                        ParamName::create('format'),
                        SchemaSerializedQuery::create($enumDescriptor),
                    )->description(Description::create('The format of the appointments')),
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
            Server::create(ServerURL::create('https://api.example.com/v1')),
            Server::create(ServerURL::create('https://api.example.com/v2')),
        ];
        $components = Components::create()->securitySchemes(ExampleHTTPBearerSecurityScheme::create());
        $security = Security::create(ExampleSingleBearerSecurityRequirement::create());
        $externalDocs = ExternalDocs::create()
            ->url('https://example.com')
            ->description('Example');
        $openApi = OpenAPI::v311($info)
            ->paths($paths)
            ->servers(...$servers)
            ->components($components)
            ->security($security)
            ->tags($tag)
            ->externalDocs($externalDocs);

        // $result = file_put_contents('openapi.json', $openApi->toJson());
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
