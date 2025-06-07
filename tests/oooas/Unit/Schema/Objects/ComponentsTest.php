<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableCallbackFactory;
use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableParameterFactory;
use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableRequestBodyFactory;
use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableResponseFactory;
use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableSchemaFactory;
use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Components;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Header;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedQuery;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Schemes\Http;

describe(class_basename(Components::class), function (): void {
    it('can be create with all parameters', function (): void {
        $mock = \Mockery::mock(ReusableSchemaFactory::class);
        $mock->allows('key')
            ->andReturn('ExampleSchema');
        $mock->expects('build')
            ->andReturn(Schema::object());

        $response = \Mockery::mock(ReusableResponseFactory::class);
        $response->allows('key')
            ->andReturn('ReusableResponse');
        $response->expects('build')
            ->andReturn(Response::deleted());

        $parameter = \Mockery::mock(ReusableParameterFactory::class);
        $parameter->allows('key')
            ->andReturn('Page');
        $parameter->expects('build')
            ->andReturn(
                Parameter::query(
                    Name::create('page'),
                    SchemaSerializedQuery::create(Schema::string()),
                ),
            );

        $example = Example::create('Page')
            ->value(5);

        $requestBody = \Mockery::mock(ReusableRequestBodyFactory::class);
        $requestBody->allows('key')
            ->andReturn('CreateResource');
        $requestBody->expects('build')
            ->andReturn(RequestBody::create());

        $header = Header::create('HeaderExample');

        $securityScheme = \Mockery::mock(SecuritySchemeFactory::class);
        $securityScheme->allows('key')
            ->andReturn('basic');
        $securityScheme->expects('build')
            ->andReturn(
                Http::basic(),
            );

        $link = Link::create('LinkExample');

        $callback = \Mockery::mock(ReusableCallbackFactory::class);
        $callback->allows('key')
            ->andReturn('MyEvent');
        $callback->expects('build')
            ->andReturn(
                Callback::create(
                    'test',
                    '{$request.query.callbackUrl}',
                    PathItem::create()
                        ->operations(
                            Operation::post()
                                ->requestBody(
                                    RequestBody::create()
                                        ->description('something happened'),
                                )->responses(
                                    Responses::create(Response::ok()),
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
            ->links($link)
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
