<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ExampleFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\HeaderFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\LinkFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\MediaTypeFactory;
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
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
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
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\QueryParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;

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
                return Response::create()->description('Deleted');
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
                return RequestBody::create(ContentEntry::json(MediaType::create()));
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
                                    ->operationId('callbackUrl')
                                    ->requestBody(
                                        RequestBody::create(ContentEntry::json(MediaType::create()))
                                            ->description(
                                                'something happened',
                                            ),
                                    )->responses(
                                        Responses::create(
                                            ResponseEntry::create(
                                                HTTPStatusCode::ok(),
                                                Response::create()->description('OK'),
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

        expect($components->compile())->toBe([
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
                'CreateResource' => [
                    'content' => [
                        'application/json' => [],
                    ],
                ],
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
                            'operationId' => 'callbackUrl',
                            'requestBody' => [
                                'description' => 'something happened',
                                'content' => [
                                    'application/json' => [],
                                ],
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

    it('can be created with mediaTypes (OAS 3.2)', function (): void {
        $mediaType = new class extends MediaTypeFactory {
            public function component(): MediaType
            {
                return MediaType::create()
                    ->schema(Schema::object());
            }

            public static function name(): string
            {
                return 'JsonContent';
            }
        };

        $components = Components::create()
            ->mediaTypes($mediaType);

        expect($components->compile())->toBe([
            'mediaTypes' => [
                'JsonContent' => [
                    'schema' => [
                        'type' => 'object',
                    ],
                ],
            ],
        ]);
    });

    it('can be created with multiple mediaTypes (OAS 3.2)', function (): void {
        $jsonMediaType = new class extends MediaTypeFactory {
            public function component(): MediaType
            {
                return MediaType::create()
                    ->schema(Schema::object());
            }

            public static function name(): string
            {
                return 'JsonPayload';
            }
        };

        $xmlMediaType = new class extends MediaTypeFactory {
            public function component(): MediaType
            {
                return MediaType::create()
                    ->schema(Schema::string());
            }

            public static function name(): string
            {
                return 'XmlPayload';
            }
        };

        $components = Components::create()
            ->mediaTypes($jsonMediaType, $xmlMediaType);

        expect($components->compile())->toBe([
            'mediaTypes' => [
                'JsonPayload' => [
                    'schema' => [
                        'type' => 'object',
                    ],
                ],
                'XmlPayload' => [
                    'schema' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ]);
    });
})->covers(Components::class);
