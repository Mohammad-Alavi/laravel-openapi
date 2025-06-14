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
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Title;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Version;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\Fields\JsonSchemaDialect;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\OpenAPI;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Description as ParamDescription;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Name as ParamName;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedPath;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedQuery;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameters;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Path;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Description as ResponseDescription;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\URL as ServerURL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Description as TagDescription;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Name as TagName;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;
use Tests\Doubles\Stubs\Petstore\Security\ExampleComplexMultiSecurityRequirementSecurity;
use Tests\Doubles\Stubs\Petstore\Security\SecuritySchemes\ExampleHTTPBearerSecurityScheme;
use Tests\Doubles\Stubs\Petstore\Security\SecuritySchemes\ExampleOAuth2PasswordSecurityScheme;

describe(class_basename(OpenAPI::class), function (): void {
    it('can be created and validated', function (): void {
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
        )->description(Description::create('For using the Example App API'))
            ->contact($contact);

        // TODO: Allow creating a Schema without a key.
        // Some schemas can be created without a key.
        //  We can call them anonymous Schemas.
        //  For example a Schema for a Response doesnt need a key.
        //  This is not possible right now.
        //  åBecause creating an Schema in anyway requires a "key".
        //  I think we should proved this functionality but I don't know how yet!
        //  Maybe we can create an AnonymousSchema class that extends Schema and doesn't require a key?
        //  Find a better name for it!
        //  Maybe Schema::anonymous()?
        // Another idea would be to create a BaseSchema class without the create method.
        //  Then create 2 Contracts, one for UnnamedSchema and another for NamedSchema.
        //  These contracts define the create method and either accept the key or not.
        // Then we accept the proper Contract when needed!
        // For example here for response we can accept the UnnamedSchema contract!
        $objectDescriptor = Schema::object()
            ->properties(
                Property::create('id', Schema::string()->format(StringFormat::UUID)),
                Property::create('created_at', Schema::string()->format(StringFormat::DATE_TIME)),
                Property::create('age', Schema::integer()),
                Property::create(
                    'data',
                    Schema::array()
                        ->items(
                            Schema::string()->format(StringFormat::UUID),
                        ),
                ),
            )->required('id', 'created_at');

        $responses = Responses::create(
            ResponseEntry::create(
                HTTPStatusCode::ok(),
                Response::create(
                    ResponseDescription::create('OK'),
                )->content(
                    ContentEntry::create(
                        'application/json',
                        MediaType::json()->schema($objectDescriptor),
                    ),
                ),
            ),
        );

        $operation = Operation::get()
            ->responses($responses)
            ->tags($tag)
            ->summary('List all audits')
            ->operationId('audits.index');

        $createAudit = Operation::post()
            ->responses($responses)
            ->tags($tag)
            ->summary('Create an audit')
            ->operationId('audits.store')
            ->requestBody(RequestBody::create()->content(
                MediaType::json()->schema($objectDescriptor),
            ));

        $stringDescriptor = Schema::string()->format(StringFormat::UUID);
        $enumDescriptor = Schema::enum('json', 'ics');

        $readAudit = Operation::get()
            ->responses($responses)
            ->tags($tag)
            ->summary('View an audit')
            ->operationId('audits.show')
            ->parameters(
                Parameters::create(
                    Parameter::path(
                        ParamName::create('audit'),
                        SchemaSerializedPath::create($stringDescriptor),
                    )->required(),
                    Parameter::query(
                        ParamName::create('format'),
                        SchemaSerializedQuery::create($enumDescriptor),
                    )->description(ParamDescription::create('The format of the appointments')),
                ),
            );

        $paths = Paths::create(
            Path::create(
                '/audits',
                PathItem::create()
                    ->operations($operation, $createAudit),
            ),
            Path::create(
                '/audits/{audit}',
                PathItem::create()
                    ->operations($readAudit),
            ),
        );

        $servers = [
            Server::create(ServerURL::create('https://api.example.com/v1')),
            Server::create(ServerURL::create('https://api.example.com/v2')),
        ];

        $security = (new ExampleComplexMultiSecurityRequirementSecurity())->build();

        $components = Components::create()->securitySchemes(
            ExampleHTTPBearerSecurityScheme::create(),
            ExampleOAuth2PasswordSecurityScheme::create(),
        );

        $externalDocumentation = ExternalDocumentation::create(
            ExtURL::create('https://example.com'),
            ExtDescription::create('Example'),
        );

        $openApi = OpenAPI::v311($info)
            ->paths($paths)
            ->servers(...$servers)
            ->components($components)
            ->security($security)
            ->tags($tag)
            ->externalDocs($externalDocumentation);

        $result = $openApi->asArray();

        expect($result)->toBe([
            'openapi' => '3.1.1',
            'info' => [
                'title' => 'API Specification',
                'description' => 'For using the Example App API',
                'contact' => [
                    'name' => 'Example',
                    'url' => 'https://example.com',
                    'email' => 'hello@example.com',
                ],
                'version' => 'v1',
            ],
            'jsonSchemaDialect' => JsonSchemaDialect::v31x()->value(),
            'servers' => [
                ['url' => 'https://api.example.com/v1'],
                ['url' => 'https://api.example.com/v2'],
            ],
            'paths' => [
                '/audits' => [
                    'get' => [
                        'tags' => ['Audits'],
                        'summary' => 'List all audits',
                        'operationId' => 'audits.index',
                        'responses' => [
                            200 => [
                                'description' => 'OK',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'id' => [
                                                    'type' => 'string',
                                                    'format' => 'uuid',
                                                ],
                                                'created_at' => [
                                                    'type' => 'string',
                                                    'format' => 'date-time',
                                                ],
                                                'age' => [
                                                    'type' => 'integer',
                                                ],
                                                'data' => [
                                                    'type' => 'array',
                                                    'items' => [
                                                        'type' => 'string',
                                                        'format' => 'uuid',
                                                    ],
                                                ],
                                            ],
                                            'required' => ['id', 'created_at'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'post' => [
                        'tags' => ['Audits'],
                        'summary' => 'Create an audit',
                        'operationId' => 'audits.store',
                        'requestBody' => [
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'id' => [
                                                'type' => 'string',
                                                'format' => 'uuid',
                                            ],
                                            'created_at' => [
                                                'type' => 'string',
                                                'format' => 'date-time',
                                            ],
                                            'age' => [
                                                'type' => 'integer',
                                            ],
                                            'data' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'string',
                                                    'format' => 'uuid',
                                                ],
                                            ],
                                        ],
                                        'required' => ['id', 'created_at'],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            200 => [
                                'description' => 'OK',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'id' => [
                                                    'type' => 'string',
                                                    'format' => 'uuid',
                                                ],
                                                'created_at' => [
                                                    'type' => 'string',
                                                    'format' => 'date-time',
                                                ],
                                                'age' => [
                                                    'type' => 'integer',
                                                ],
                                                'data' => [
                                                    'type' => 'array',
                                                    'items' => [
                                                        'type' => 'string',
                                                        'format' => 'uuid',
                                                    ],
                                                ],
                                            ],
                                            'required' => ['id', 'created_at'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/audits/{audit}' => [
                    'get' => [
                        'tags' => ['Audits'],
                        'summary' => 'View an audit',
                        'operationId' => 'audits.show',
                        'parameters' => [
                            [
                                'name' => 'audit',
                                'in' => 'path',
                                'required' => true,
                                'schema' => [
                                    'type' => 'string',
                                    'format' => 'uuid',
                                ],
                            ],
                            [
                                'name' => 'format',
                                'in' => 'query',
                                'description' => 'The format of the appointments',
                                'schema' => [
                                    'enum' => ['json', 'ics'],
                                ],
                            ],
                        ],
                        'responses' => [
                            200 => [
                                'description' => 'OK',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'id' => [
                                                    'type' => 'string',
                                                    'format' => 'uuid',
                                                ],
                                                'created_at' => [
                                                    'type' => 'string',
                                                    'format' => 'date-time',
                                                ],
                                                'age' => [
                                                    'type' => 'integer',
                                                ],
                                                'data' => [
                                                    'type' => 'array',
                                                    'items' => [
                                                        'type' => 'string',
                                                        'format' => 'uuid',
                                                    ],
                                                ],
                                            ],
                                            'required' => ['id', 'created_at'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'components' => [
                'securitySchemes' => [
                    'ExampleHTTPBearerSecurityScheme' => [
                        'type' => 'http',
                        'description' => 'Example Security',
                        'scheme' => 'bearer',
                    ],
                    'OAuth2Password' => [
                        'type' => 'oauth2',
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
                    ],
                ],
            ],
            'security' => [
                [
                    'ExampleHTTPBearerSecurityScheme' => [],
                ],
                [
                    'ExampleHTTPBearerSecurityScheme' => [],
                    'OAuth2Password' => [
                        'order:shipping:address',
                        'order:shipping:status',
                    ],
                ],
            ],
            'tags' => [
                ['name' => 'Audits', 'description' => 'All the audits'],
            ],
            'externalDocs' => [
                'url' => 'https://example.com',
                'description' => 'Example',
            ],
        ]);
    });
})->covers(OpenAPI::class);
