<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ExampleFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\HeaderFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\LinkFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ParameterFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\PathItemFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\RequestBodyFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ResponseFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Components;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Header;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\QueryParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\AvailableOperation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\HttpMethod;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\Http;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\SecurityScheme;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression\Request\RequestQueryExpression;

describe(class_basename(Components::class), function (): void {
    it('can be create with all parameters', function (): void {
        $schema = new class extends SchemaFactory {
            public function component(): JSONSchema
            {
                return Schema::object();
            }

            public static function name(): string
            {
                return 'ExampleSchema';
            }
        };

        $response = new class extends ResponseFactory {
            public function component(): Response
            {
                return Response::create('Deleted');
            }

            public static function name(): string
            {
                return 'ReusableResponse';
            }
        };

        $parameter = new class extends ParameterFactory {
            public function component(): Parameter
            {
                return Parameter::query(
                    'page',
                    QueryParameter::create(Schema::string()),
                );
            }

            public static function name(): string
            {
                return 'Page';
            }
        };

        $example = new class extends ExampleFactory {
            public function component(): Example
            {
                return Example::create()
                    ->value(5);
            }

            public static function name(): string
            {
                return 'Example';
            }
        };

        $requestBody = new class extends RequestBodyFactory {
            public function component(): RequestBody
            {
                return RequestBody::create();
            }

            public static function name(): string
            {
                return 'CreateResource';
            }
        };

        $header = new class extends HeaderFactory {
            public function component(): Header
            {
                return Header::create();
            }

            public static function name(): string
            {
                return 'HeaderExample';
            }
        };

        $securityScheme = new class extends SecuritySchemeFactory {
            public function component(): SecurityScheme
            {
                return SecurityScheme::http(Http::basic());
            }

            public static function name(): string
            {
                return 'basic';
            }
        };

        $link = new class extends LinkFactory {
            public function component(): Link
            {
                return Link::create();
            }

            public static function name(): string
            {
                return 'LinkExample';
            }
        };

        $callback = new class extends CallbackFactory {
            public function component(): Callback
            {
                return Callback::create(
                    RequestQueryExpression::create('callbackUrl'),
                    PathItem::create()
                        ->operations(
                            AvailableOperation::create(
                                HttpMethod::POST,
                                Operation::create()
                                    ->requestBody(
                                        RequestBody::create()
                                            ->description(
                                                'something happened',
                                            ),
                                    )->responses(
                                        Responses::create(
                                            ResponseEntry::create(
                                                HTTPStatusCode::ok(),
                                                Response::create(
                                                    'OK',
                                                ),
                                            ),
                                        ),
                                    ),
                            ),
                        ),
                );
            }

            public static function name(): string
            {
                return 'MyEvent';
            }
        };

        $pathItem = new class extends PathItemFactory {
            public function component(): PathItem
            {
                return PathItem::create();
            }

            public static function name(): string
            {
                return 'PathItemExample';
            }
        };

        $components = Components::create()
            ->schemas($schema)
            ->responses($response)
            ->parameters($parameter)
            ->examples($example)
            ->requestBodies($requestBody)
            ->headers($header)
            ->securitySchemes($securityScheme)
            ->links($link)
            ->callbacks($callback)
            ->pathItems($pathItem);

        expect($components->unserializeToArray())->toBe([
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
                'Example' => [
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
            'pathItems' => [
                'PathItemExample' => [],
            ],
        ]);
    });
})->covers(Components::class);
