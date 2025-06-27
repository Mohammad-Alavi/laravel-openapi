<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\Fields\URL as ExtURL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Fields\Description as OperationDescription;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Fields\OperationId;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Fields\Summary;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Name as ParamName;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedQuery;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Description as ResponseDescription;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\SharedFields\Parameters;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;
use Tests\src\Support\Doubles\Stubs\Attributes\TestCallbackFactory;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\SecuritySchemes\TestBearerSecuritySchemeFactory;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\TestSingleHTTPBearerSchemeSecurityFactory;

describe(class_basename(Operation::class), function (): void {
    it('can be created with no parameters', function (): void {
        $operation = Operation::create();

        expect($operation->asArray())->toBeEmpty();
    });

    it(
        'can can be created with all parameters',
        function (): void {
            $operation = Operation::create()
                ->tags(
                    Tag::create(
                        Name::create('Users'),
                        Description::create('Lorem ipsum'),
                    ),
                )->summary(Summary::create('Lorem ipsum'))
                ->description(OperationDescription::create('Dolar sit amet'))
                ->externalDocs(ExternalDocumentation::create(ExtURL::create('https://example.com/docs')))
                ->operationId(OperationId::create('users.show'))
                ->parameters(
                    Parameters::create(
                        Parameter::query(
                            ParamName::create('id'),
                            SchemaSerializedQuery::create(Schema::string()),
                        ),
                    ),
                )->requestBody(RequestBody::create())
                ->responses(
                    Responses::create(
                        ResponseEntry::create(
                            HTTPStatusCode::ok(),
                            Response::create(ResponseDescription::create('OK')),
                        ),
                    ),
                )->deprecated()
                ->security(app(TestSingleHTTPBearerSchemeSecurityFactory::class)->build())
                ->servers(Server::default())
                ->callbacks(TestCallbackFactory::create());

            expect($operation->asArray())->toBe([
                'tags' => ['Users'],
                'summary' => 'Lorem ipsum',
                'description' => 'Dolar sit amet',
                'externalDocs' => [
                    'url' => 'https://example.com/docs',
                ],
                'operationId' => 'users.show',
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'query',
                        'schema' => [
                            'type' => 'string',
                        ],
                    ],
                ],
                'requestBody' => [],
                'responses' => [
                    '200' => [
                        'description' => 'OK',
                    ],
                ],
                'deprecated' => true,
                'security' => [
                    [
                        TestBearerSecuritySchemeFactory::name() => [],
                    ],
                ],
                'servers' => [
                    [
                        'url' => '/',
                    ],
                ],
                'callbacks' => [
                    'CallbackFactory' => [
                        'https://example.com/' => [],
                    ],
                ],
            ]);
        },
    );

    it('can accepts tags in multiple ways', function (array $tag, $expectation): void {
        $operation = Operation::create()
            ->responses(
                Responses::create(
                    ResponseEntry::create(
                        HTTPStatusCode::ok(),
                        Response::create(ResponseDescription::create('OK')),
                    ),
                ),
            )
            ->tags(...$tag);

        expect($operation->asArray())->toBe([
            'tags' => $expectation,
            'responses' => [
                '200' => [
                    'description' => 'OK',
                ],
            ],
        ]);
    })->with([
        'one string tag' => [
            ['Users'],
            ['Users']],
        'multiple string tags' => [
            ['Users', 'Admins'],
            ['Users', 'Admins'],
        ],
        'one object tag' => [
            [Tag::create(Name::create('Users'))],
            ['Users'],
        ],
        'multiple object tags' => [
            [Tag::create(Name::create('Users')), Tag::create(Name::create('Admins'))],
            ['Users', 'Admins'],
        ],
        'mixed tags' => [
            ['Users', Tag::create(Name::create('Admins'))],
            ['Users', 'Admins'],
        ],
    ]);
})->covers(Operation::class);
