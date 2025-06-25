<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ParameterFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\RequestBodyFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ResponseFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Components;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Header;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedQuery;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\AvailableOperation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\HttpMethod;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\Http;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\SecurityScheme;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\Collections\Headers\HeaderEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\Collections\Links\LinkEntry;

describe(class_basename(Components::class), function (): void {
    it('can be create with all parameters', function (): void {
        $mock = \Mockery::mock(SchemaFactory::class);
        $mock->allows('name')
            ->andReturn('ExampleSchema');
        $mock->expects('component')
            ->andReturn(Schema::object());

        $response = \Mockery::mock(ResponseFactory::class);
        $response->allows('name')
            ->andReturn('ReusableResponse');
        $response->expects('component')
            ->andReturn(Response::create(Description::create('Deleted')));

        $parameter = \Mockery::mock(ParameterFactory::class);
        $parameter->allows('name')
            ->andReturn('Page');
        $parameter->expects('component')
            ->andReturn(
                Parameter::query(
                    Name::create('page'),
                    SchemaSerializedQuery::create(Schema::string()),
                ),
            );

        $example = Example::create('Page')
            ->value(5);

        $requestBody = \Mockery::mock(RequestBodyFactory::class);
        $requestBody->allows('name')
            ->andReturn('CreateResource');
        $requestBody->expects('component')
            ->andReturn(RequestBody::create());

        $header = HeaderEntry::create(
            'HeaderExample',
            Header::create(),
        );

        $securityScheme = \Mockery::mock(SecuritySchemeFactory::class);
        $securityScheme->allows('name')
            ->andReturn('basic');
        $securityScheme->expects('component')
            ->andReturn(
                SecurityScheme::http(Http::basic()),
            );

        $link = Link::create();

        $callback = \Mockery::mock(CallbackFactory::class);
        $callback->allows('name')
            ->andReturn('MyEvent');
        $callback->expects('component')
            ->andReturn(
                Callback::create(
                    'test',
                    '{$request.query.callbackUrl}',
                    PathItem::create()
                        ->operations(
                            AvailableOperation::create(
                                HttpMethod::POST,
                                Operation::create()
                                    ->requestBody(
                                        RequestBody::create()
                                            ->description('something happened'),
                                    )->responses(
                                        Responses::create(
                                            ResponseEntry::create(
                                                HTTPStatusCode::ok(),
                                                Response::create(
                                                    Description::create('OK'),
                                                ),
                                            ),
                                        ),
                                    ),
                            ),
                        ),
                ),
            );

        $components = Components::create()
            ->schemas($mock)
            ->responses($response)
            ->parameters($parameter)
            ->examples($example)
            ->requestBodies($requestBody)
            ->headers($header)
            ->securitySchemes($securityScheme)
            ->links(LinkEntry::create('LinkExample', $link))
            ->callbacks($callback);

        expect($components->asArray())->toBe([
            'schemas' => [
                'ExampleSchema' => [
                    'type' => 'object',
                ],
            ],
            'responses' => [
                'ReusableResponse' => [
                    'description' => 'Deleted',
                ],
            ],
            'parameters' => [
                'Page' => [
                    'name' => 'page',
                    'in' => 'query',
                    'schema' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'examples' => [
                'Page' => [
                    'value' => 5,
                ],
            ],
            'requestBodies' => [
                'CreateResource' => [],
            ],
            'headers' => [
                'HeaderExample' => [],
            ],
            'securitySchemes' => [
                'basic' => [
                    'type' => 'http',
                    'scheme' => 'basic',
                ],
            ],
            'links' => [
                'LinkExample' => [],
            ],
            'callbacks' => [
                'MyEvent' => [
                    '{$request.query.callbackUrl}' => [
                        'post' => [
                            'requestBody' => [
                                'description' => 'something happened',
                            ],
                            'responses' => [
                                '200' => [
                                    'description' => 'OK',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    });
})->covers(Components::class);
