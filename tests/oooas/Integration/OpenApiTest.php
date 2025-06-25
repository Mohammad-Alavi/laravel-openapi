<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Components;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Contact;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Fields\Email;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Fields\URL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\Fields\Description as ExtDescription;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\Fields\URL as ExtURL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Description as InfoDescription;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Title;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Version;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\OpenAPI;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Fields\OperationId;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Fields\Summary;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Name as ParamName;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedPath;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedQuery;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\AvailableOperation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\HttpMethod;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths\Fields\Path;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths\Paths;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Description as ResponseDescription;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\URL as ServerURL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\SharedFields\Parameters;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Description as TagDescription;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Name as TagName;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\SecurityRequirements\TestBearerSecurityRequirementFactory;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\SecuritySchemes\TestBearerSecuritySchemeFactory;

describe('OpenApi', function (): void {
    it('can generate valid OpenAPI v3.1.0 docs', function (): void {
        $tag = Tag::create(
            TagName::create('Audits'),
            TagDescription::create('All the audits'),
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
                        Schema::allOf(
                            Schema::string()->format(StringFormat::UUID),
                        ),
                    ),
                ),
            )->required('id', 'created_at');
        $responses = Responses::create(
            ResponseEntry::create(
                HTTPStatusCode::ok(),
                Response::create(
                    ResponseDescription::create('OK'),
                )->content(
                    ContentEntry::json(
                        MediaType::create()->schema($objectDescriptor),
                    ),
                ),
            ),
        );
        $indexOperation = Operation::create()
            ->responses($responses)
            ->tags($tag)
            ->summary(Summary::create('List all audits'))
            ->operationId(OperationId::create('audits.index'));
        $postOperation = Operation::create()
            ->responses($responses)
            ->tags($tag)
            ->summary(Summary::create('Create an audit'))
            ->operationId(OperationId::create('audits.store'))
            ->requestBody(
                RequestBody::create()
                    ->content(
                        ContentEntry::json(
                            MediaType::create()->schema($objectDescriptor),
                        ),
                    ),
            );
        $stringDescriptor = Schema::string()->format(StringFormat::UUID);
        $enumDescriptor = Schema::enum('json', 'ics')
            ->default('json');
        $getOperation = Operation::create()
            ->responses($responses)
            ->tags($tag)
            ->summary(Summary::create('View an audit'))
            ->operationId(OperationId::create('audits.show'))
            ->parameters(
                Parameters::create(
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
                    ->operations(
                        AvailableOperation::create(
                            HttpMethod::GET,
                            $indexOperation,
                        ),
                        AvailableOperation::create(
                            HttpMethod::POST,
                            $postOperation,
                        ),
                    ),
            ),
            Path::create(
                '/audits/{audit}',
                PathItem::create()
                    ->operations(
                        AvailableOperation::create(
                            HttpMethod::GET,
                            $getOperation,
                        ),
                    ),
            ),
        );
        $servers = [
            Server::create(ServerURL::create('https://api.example.com/v1')),
            Server::create(ServerURL::create('https://api.example.com/v2')),
        ];
        $components = Components::create()->securitySchemes(TestBearerSecuritySchemeFactory::create());
        $security = Security::create(TestBearerSecurityRequirementFactory::create());
        $externalDocumentation = ExternalDocumentation::create(
            ExtURL::create('https://example.com/docs'),
            ExtDescription::create('Example'),
        );
        $openApi = OpenAPI::v311($info)
            ->paths($paths)
            ->servers(...$servers)
            ->components($components)
            ->security($security)
            ->tags($tag)
            ->externalDocs($externalDocumentation);

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
