<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\Content;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\CookieParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\HeaderParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\PathParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\QueryParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\ExampleEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles\Cookie;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles\DeepObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles\Form;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles\Label;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles\Matrix;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles\PipeDelimited;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles\Simple;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles\SpaceDelimited;

describe('Parameter', function (): void {
    it(
        'can create cookie parameter',
        function (
            Cookie|Form|null $style,
            array $expected,
        ): void {
            $parameter = Parameter::cookie(
                'user',
                CookieParameter::create(
                    Schema::integer(),
                    $style,
                ),
            )->examples(
                ExampleEntry::create(
                    'example_test',
                    Example::create(),
                ),
                ExampleEntry::create(
                    'ExampleName',
                    Example::create(),
                ),
            )->description('User ID')
                ->required()
                ->deprecated();

            expect($parameter->compile())->toBe([
                'name' => 'user',
                'in' => 'cookie',
                'description' => 'User ID',
                'required' => true,
                'deprecated' => true,
                ...$expected,
                'schema' => [
                    'type' => 'integer',
                ],
                'examples' => [
                    'example_test' => [],
                    'ExampleName' => [],
                ],
            ]);
        },
    )->with([
        'form' => [
            Form::create()->explode(),
            [
                'style' => 'form',
                'explode' => true,
            ],
        ],
        'cookie' => [
            Cookie::create()->explode(),
            [
                'style' => 'cookie',
                'explode' => true,
            ],
        ],
        'null' => [
            null,
            [],
        ],
    ]);

    it(
        'can create header parameter',
        function (
            Simple|null $style,
            array $expected,
        ): void {
            $parameter = Parameter::header(
                'user',
                HeaderParameter::create(
                    Schema::object(),
                    $style,
                ),
            )->examples(
                ExampleEntry::create(
                    'example_test',
                    Example::create(),
                ),
            )->description('User ID')
                ->deprecated();

            expect($parameter->compile())->toBe([
                'name' => 'user',
                'in' => 'header',
                'description' => 'User ID',
                'deprecated' => true,
                ...$expected,
                'schema' => [
                    'type' => 'object',
                ],
                'examples' => [
                    'example_test' => [],
                ],
            ]);
        },
    )->with([
        'simple' => [
            Simple::create()->explode(),
            [
                'style' => 'simple',
                'explode' => true,
            ],
        ],
        'null' => [
            null,
            [],
        ],
    ]);

    it(
        'can create path parameter',
        function (
            Label|Matrix|Simple|null $style,
            array $expected,
        ): void {
            // Note: per OAS 3.2, path parameters should have required() called
            $parameter = Parameter::path(
                'user',
                PathParameter::create(
                    Schema::string(),
                    $style,
                ),
            )->description('User ID')
                ->required();

            expect($parameter->compile())->toBe([
                'name' => 'user',
                'in' => 'path',
                'description' => 'User ID',
                'required' => true,
                ...$expected,
                'schema' => [
                    'type' => 'string',
                ],
            ]);
        },
    )->with([
        'label' => [
            Label::create()->explode(),
            [
                'style' => 'label',
                'explode' => true,
            ],
        ],
        'matrix' => [
            Matrix::create()->explode(),
            [
                'style' => 'matrix',
                'explode' => true,
            ],
        ],
        'simple' => [
            Simple::create(),
            [
                'style' => 'simple',
            ],
        ],
        'null' => [
            null,
            [],
        ],
    ]);

    it(
        'can create query parameter',
        function (
            DeepObject|Form|PipeDelimited|SpaceDelimited|null $style,
            bool $allowReserved,
            array $expected,
        ): void {
            $queryParam = QueryParameter::create(
                Schema::array(),
                $style,
            );

            if ($allowReserved) {
                $queryParam = $queryParam->allowReserved();
            }

            $parameter = Parameter::query(
                'user',
                $queryParam,
            )->description('User ID')
                ->required()
                ->deprecated();

            expect($parameter->compile())->toBe([
                'name' => 'user',
                'in' => 'query',
                'description' => 'User ID',
                'required' => true,
                'deprecated' => true,
                ...$expected,
                'schema' => [
                    'type' => 'array',
                ],
            ]);
        },
    )->with([
        'deepObject' => [
            DeepObject::create(),
            true,
            [
                'style' => 'deepObject',
                'allowReserved' => true,
            ],
        ],
        'form' => [
            Form::create(),
            false,
            [
                'style' => 'form',
            ],
        ],
        'pipeDelimited' => [
            PipeDelimited::create(),
            true,
            [
                'style' => 'pipeDelimited',
                'allowReserved' => true,
            ],
        ],
        'spaceDelimited' => [
            SpaceDelimited::create()->explode(),
            false,
            [
                'style' => 'spaceDelimited',
                'explode' => true,
            ],
        ],
        'null' => [
            null,
            false,
            [],
        ],
    ]);

    it(
        'can serialize content',
        function (Content $contentSerialized, array $expected): void {
            $parameter = Parameter::query(
                'user',
                $contentSerialized,
            )->description('User ID')
                ->required()
                ->deprecated();

            expect($parameter->compile())->toBe([
                'name' => 'user',
                'in' => 'query',
                'description' => 'User ID',
                'required' => true,
                'deprecated' => true,
                ...$expected,
            ]);
        },
    )->with([
        'contentSerialized' => [
            Content::create(
                ContentEntry::pdf(
                    MediaType::create(),
                ),
            ),
            [
                'content' => [
                    'application/pdf' => [],
                ],
            ],
        ],
    ]);

    it('path parameters should have required() called for OAS 3.2 compliance', function (): void {
        // Per OAS 3.2, path parameters MUST have required=true
        // This test verifies the API supports setting it explicitly
        $parameter = Parameter::path(
            'id',
            PathParameter::create(Schema::string()),
        )->required();

        expect($parameter->compile())->toHaveKey('required', true);
    });

    it('allowEmptyValue works for query parameters', function (): void {
        $parameter = Parameter::query(
            'filter',
            QueryParameter::create(Schema::string()),
        )->allowEmptyValue();

        expect($parameter->compile())->toHaveKey('allowEmptyValue', true);
    });

    it('allowEmptyValue throws for non-query parameters', function (string $location): void {
        $parameter = match ($location) {
            'path' => Parameter::path('id', PathParameter::create(Schema::string())),
            'header' => Parameter::header('X-Custom', HeaderParameter::create(Schema::string())),
            'cookie' => Parameter::cookie('session', CookieParameter::create(Schema::string())),
        };

        expect(fn () => $parameter->allowEmptyValue())
            ->toThrow(
                InvalidArgumentException::class,
                'allowEmptyValue is only valid for query parameters',
            );
    })->with(['path', 'header', 'cookie']);

    describe('explode field on style', function (): void {
        it('can explicitly set explode to false on form style', function (): void {
            $parameter = Parameter::query(
                'filter',
                QueryParameter::create(
                    Schema::array(),
                    Form::create()->explode(false),
                ),
            );

            expect($parameter->compile())->toBe([
                'name' => 'filter',
                'in' => 'query',
                'style' => 'form',
                'explode' => false,
                'schema' => [
                    'type' => 'array',
                ],
            ]);
        });

        it('can explicitly set explode to false on cookie style', function (): void {
            $parameter = Parameter::cookie(
                'session',
                CookieParameter::create(
                    Schema::string(),
                    Cookie::create()->explode(false),
                ),
            );

            expect($parameter->compile())->toBe([
                'name' => 'session',
                'in' => 'cookie',
                'style' => 'cookie',
                'explode' => false,
                'schema' => [
                    'type' => 'string',
                ],
            ]);
        });
    });

    describe('example and examples fields', function (): void {
        it('can use example (singular) field', function (): void {
            $parameter = Parameter::query(
                'status',
                QueryParameter::create(Schema::string()),
            )->example('active');

            expect($parameter->compile())->toBe([
                'name' => 'status',
                'in' => 'query',
                'schema' => [
                    'type' => 'string',
                ],
                'example' => 'active',
            ]);
        });

        it('can use examples (plural) via fluent method', function (): void {
            $parameter = Parameter::query(
                'status',
                QueryParameter::create(Schema::string()),
            )->examples(
                ExampleEntry::create('active', Example::create()->value('active')),
                ExampleEntry::create('inactive', Example::create()->value('inactive')),
            );

            expect($parameter->compile())->toBe([
                'name' => 'status',
                'in' => 'query',
                'schema' => [
                    'type' => 'string',
                ],
                'examples' => [
                    'active' => ['value' => 'active'],
                    'inactive' => ['value' => 'inactive'],
                ],
            ]);
        });

        it('example field works with complex values', function (): void {
            $parameter = Parameter::query(
                'filter',
                QueryParameter::create(Schema::object()),
            )->example(['status' => 'active', 'type' => 'user']);

            expect($parameter->compile()['example'])->toBe(['status' => 'active', 'type' => 'user']);
        });

        it('prevents setting examples after example', function (): void {
            expect(fn () => Parameter::query(
                'status',
                QueryParameter::create(Schema::string()),
            )->example('value')
                ->examples(ExampleEntry::create('test', Example::create())))
                ->toThrow(
                    InvalidArgumentException::class,
                    'examples and example fields are mutually exclusive',
                );
        });
    });

    describe('allowReserved on SchemaSerialized', function (): void {
        it('works on cookie parameter', function (): void {
            $parameter = Parameter::cookie(
                'token',
                CookieParameter::create(
                    Schema::string(),
                    Form::create()->explode(),
                )->allowReserved(),
            );

            expect($parameter->compile())->toBe([
                'name' => 'token',
                'in' => 'cookie',
                'style' => 'form',
                'explode' => true,
                'allowReserved' => true,
                'schema' => [
                    'type' => 'string',
                ],
            ]);
        });

        it('works on header parameter', function (): void {
            $parameter = Parameter::header(
                'X-Custom',
                HeaderParameter::create(Schema::string())->allowReserved(),
            );

            expect($parameter->compile())->toBe([
                'name' => 'X-Custom',
                'in' => 'header',
                'allowReserved' => true,
                'schema' => [
                    'type' => 'string',
                ],
            ]);
        });
    });
})->covers(Parameter::class);
