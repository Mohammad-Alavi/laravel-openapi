<?php

use MohammadAlavi\LaravelOpenApi\Collections\ParameterCollection;
use MohammadAlavi\LaravelOpenApi\Collections\Path;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Enums\OASVersion;
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
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag;
use Tests\Doubles\Stubs\Petstore\Security\ExampleComplexMultiSecurityRequirementSecurity;
use Tests\Doubles\Stubs\Petstore\Security\SecuritySchemes\ExampleHTTPBearerSecurityScheme;
use Tests\Doubles\Stubs\Petstore\Security\SecuritySchemes\ExampleOAuth2PasswordSecurityScheme;

describe(class_basename(OpenApi::class), function (): void {
    it('can be created and validated', function (): void {
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
        $objectBuilder = Schema::object()
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

        $expectedResponse = Response::ok()
            ->content(
                MediaType::json()->schema($objectBuilder),
            );

        $operation = Operation::get()
            ->responses(Responses::create($expectedResponse))
            ->tags($tag)
            ->summary('List all audits')
            ->operationId('audits.index');

        $createAudit = Operation::post()
            ->responses(Responses::create($expectedResponse))
            ->tags($tag)
            ->summary('Create an audit')
            ->operationId('audits.store')
            ->requestBody(RequestBody::create()->content(
                MediaType::json()->schema($objectBuilder),
            ));

        $stringBuilder = Schema::string()->format(StringFormat::UUID);
        $enumBuilder = Schema::enumerator('json', 'ics');

        $readAudit = Operation::get()
            ->responses(Responses::create($expectedResponse))
            ->tags($tag)
            ->summary('View an audit')
            ->operationId('audits.show')
            ->parameters(
                ParameterCollection::create(
                    Parameter::path()
                        ->name('audit')
                        ->schema($stringBuilder)
                        ->required(),
                    Parameter::query()
                        ->name('format')
                        ->schema($enumBuilder)
                        ->description('The format of the appointments'),
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
            Server::create()->url('https://api.example.com/v1'),
            Server::create()->url('https://api.example.com/v2'),
        ];

        $security = (new ExampleComplexMultiSecurityRequirementSecurity())->build();

        $components = Components::create()->securitySchemes(
            ExampleHTTPBearerSecurityScheme::create(),
            ExampleOAuth2PasswordSecurityScheme::create(),
        );

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

        $result = $openApi->asArray();

        expect($result)->toBe([
            'openapi' => OASVersion::V_3_1_0->value,
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
                'description' => 'Example',
                'url' => 'https://example.com',
            ],
        ]);
    });
})->covers(OpenApi::class);
