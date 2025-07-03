<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Components;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Contact;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\Fields\JsonSchemaDialect;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\OpenAPI;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\PathParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\QueryParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\AvailableOperation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\HttpMethod;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths\Fields\Path;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths\Paths;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Parameters;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\SecuritySchemes\TestBearerSecuritySchemeFactory;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\SecuritySchemes\TestOAuth2PasswordSecuritySchemeFactory;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\TestComplexMultiSecurityFactory;

describe(class_basename(OpenAPI::class), function (): void {
    it('can be created and validated', function (): void {
        $tag = Tag::create('Audits')->description('All the audits');

        $contact = Contact::create()
            ->name('Example')
            ->url('https://laragen.io')
            ->email('hello@laragen.io');

        $info = Info::create(
            'API Specification',
            'v1',
        )->description('For using the Example App API')
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
                    'OK',
                )->content(
                    ContentEntry::json(
                        MediaType::create()->schema($objectDescriptor),
                    ),
                ),
            ),
        );

        $operation = Operation::create()
            ->responses($responses)
            ->tags($tag)
            ->summary('List all audits')
            ->operationId('audits.index');

        $createAudit = Operation::create()
            ->responses($responses)
            ->tags($tag)
            ->summary('Create an audit')
            ->operationId('audits.store')
            ->requestBody(
                RequestBody::create()
                    ->content(
                        ContentEntry::json(
                            MediaType::create()->schema($objectDescriptor),
                        ),
                    ),
            );

        $stringDescriptor = Schema::string()->format(StringFormat::UUID);
        $enumDescriptor = Schema::enum('json', 'ics');

        $readAudit = Operation::create()
            ->responses($responses)
            ->tags($tag)
            ->summary('View an audit')
            ->operationId('audits.show')
            ->parameters(
                Parameters::create(
                    Parameter::path(
                        'audit',
                        PathParameter::create($stringDescriptor),
                    )->required(),
                    Parameter::query(
                        'format',
                        QueryParameter::create($enumDescriptor),
                    )->description('The format of the appointments'),
                ),
            );

        $paths = Paths::create(
            Path::create(
                '/audits',
                PathItem::create()
                    ->operations(
                        AvailableOperation::create(
                            HttpMethod::GET,
                            $operation,
                        ),
                        AvailableOperation::create(
                            HttpMethod::POST,
                            $createAudit,
                        ),
                    ),
            ),
            Path::create(
                '/audits/{audit}',
                PathItem::create()
                    ->operations(
                        AvailableOperation::create(
                            HttpMethod::GET,
                            $readAudit,
                        ),
                    ),
            ),
        );

        $servers = [
            Server::create('https://api.laragen.io/v1'),
            Server::create('https://api.laragen.io/v2'),
        ];

        $security = app(TestComplexMultiSecurityFactory::class)->build();

        $components = Components::create()->securitySchemes(
            TestBearerSecuritySchemeFactory::create(),
            TestOAuth2PasswordSecuritySchemeFactory::create(),
        );

        $externalDocumentation = ExternalDocumentation::create('https://laragen.io')
            ->description('Example');

        $openApi = OpenAPI::v311($info)
            ->paths($paths)
            ->servers(...$servers)
            ->components($components)
            ->security($security)
            ->tags($tag)
            ->externalDocs($externalDocumentation);

        $result = $openApi->unserializeToArray();

        expect($result)->toBe([
            'openapi' => '3.1.1',
            'info' => [
                'title' => 'API Specification',
                'description' => 'For using the Example App API',
                'contact' => [
                    'name' => 'Example',
                    'url' => 'https://laragen.io',
                    'email' => 'hello@laragen.io',
                ],
                'version' => 'v1',
            ],
            'jsonSchemaDialect' => JsonSchemaDialect::v31x()->value(),
            'servers' => [
                ['url' => 'https://api.laragen.io/v1'],
                ['url' => 'https://api.laragen.io/v2'],
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
                    TestBearerSecuritySchemeFactory::name() => [
                        'type' => 'http',
                        'description' => 'Example Security',
                        'scheme' => 'bearer',
                    ],
                    'OAuth2Password' => [
                        'type' => 'oauth2',
                        'description' => 'OAuth2 Password Security',
                        'flows' => [
                            'password' => [
                                'tokenUrl' => 'https://laragen.io/oauth/authorize',
                                'refreshUrl' => 'https://laragen.io/oauth/token',
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
                    TestBearerSecuritySchemeFactory::name() => [],
                ],
                [
                    TestBearerSecuritySchemeFactory::name() => [],
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
                'url' => 'https://laragen.io',
                'description' => 'Example',
            ],
        ]);
    });
})->covers(OpenAPI::class);
