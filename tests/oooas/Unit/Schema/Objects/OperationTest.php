<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\Fields\URL as ExtURL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Name as ParamName;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedQuery;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ParameterCollection;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Description as ResponseDescription;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;
use Tests\Doubles\Stubs\Petstore\Security\ExampleSingleSecurityRequirementSecurity;

describe('Operation', function (): void {
    it('can be created with no parameters', function (): void {
        $operation = Operation::create();

        expect($operation->asArray())->toBeEmpty();
    });

    it(
        'can can be created with all parameters',
        function (string $actionMethod, string $operationName): void {
            $callback =
                Callback::create(
                    'MyEvent',
                    '{$request.query.callbackUrl}',
                    PathItem::create()
                        ->operations(
                            Operation::$actionMethod()
                                ->requestBody(
                                    RequestBody::create()
                                        ->description('something happened'),
                                )->responses(
                                    Responses::create(
                                        ResponseEntry::create(
                                            HTTPStatusCode::unauthorized(),
                                            Response::create(ResponseDescription::create('Unauthorized')),
                                        ),
                                    ),
                                ),
                        ),
                );

            $operation = Operation::create()
                ->action(Operation::ACTION_GET)
                ->tags(
                    Tag::create(
                        Name::create('Users'),
                        Description::create('Lorem ipsum'),
                    ),
                )->summary('Lorem ipsum')
                ->description('Dolar sit amet')
                ->externalDocs(ExternalDocumentation::create(ExtURL::create('https://example.com/docs')))
                ->operationId('users.show')
                ->parameters(
                    ParameterCollection::create(
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
                ->security((new ExampleSingleSecurityRequirementSecurity())->build())
                ->servers(Server::default())
                ->callbacks($callback);

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
                        'ExampleHTTPBearerSecurityScheme' => [],
                    ],
                ],
                'servers' => [
                    [
                        'url' => '/',
                    ],
                ],
                'callbacks' => [
                    'MyEvent' => [
                        '{$request.query.callbackUrl}' => [
                            $operationName => [
                                'requestBody' => [
                                    'description' => 'something happened',
                                ],
                                'responses' => [
                                    '401' => [
                                        'description' => 'Unauthorized',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]);
        },
    )->with([
        'get action' => ['get', Operation::ACTION_GET],
        'put action' => ['put', Operation::ACTION_PUT],
        'post action' => ['post', Operation::ACTION_POST],
        'delete action' => ['delete', Operation::ACTION_DELETE],
        'options action' => ['options', Operation::ACTION_OPTIONS],
        'head action' => ['head', Operation::ACTION_HEAD],
        'patch action' => ['patch', Operation::ACTION_PATCH],
        'trace action' => ['trace', Operation::ACTION_TRACE],
    ]);

    it('can be created with now security', function (): void {
        $operation = Operation::get()
            ->noSecurity();

        expect($operation->asArray())->toBe([
            'security' => [],
        ]);
    })->skip('update the implementation to support no security');

    it('can accepts tags in multiple ways', function (array $tag, $expectation): void {
        $operation = Operation::get()
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
