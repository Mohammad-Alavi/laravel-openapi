<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\Content;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\CookieParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\HeaderParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\PathParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\QueryParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\ExampleEntry;
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
            Form|null $style,
            array $expected,
        ): void {
            $parameter = Parameter::cookie(
                'user',
                CookieParameter::create(
                    Schema::integer(),
                    $style,
                    ExampleEntry::create(
                        'example_test',
                        Example::create(),
                    ),
                    ExampleEntry::create(
                        'ExampleName',
                        Example::create(),
                    ),
                ),
            )->description('User ID')
                ->required()
                ->deprecated()
                ->allowEmptyValue();

            expect($parameter->compile())->toBe([
                'name' => 'user',
                'in' => 'cookie',
                'description' => 'User ID',
                'required' => true,
                'deprecated' => true,
                'allowEmptyValue' => true,
                'schema' => [
                    'type' => 'integer',
                ],
                ...$expected,
                'examples' => [
                    'example_test' => [],
                    'ExampleName' => [],
                ],
            ]);
        },
    )->with([
        'form' => [
            Form::create()->explode()->allowReserved(),
            [
                'style' => 'form',
                'explode' => true,
                'allowReserved' => true,
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
                    ExampleEntry::create(
                        'example_test',
                        Example::create(),
                    ),
                ),
            )->description('User ID')
                ->deprecated()
                ->allowEmptyValue();

            expect($parameter->compile())->toBe([
                'name' => 'user',
                'in' => 'header',
                'description' => 'User ID',
                'deprecated' => true,
                'allowEmptyValue' => true,
                'schema' => [
                    'type' => 'object',
                ],
                ...$expected,
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
            $parameter = Parameter::path(
                'user',
                PathParameter::create(
                    Schema::string(),
                    $style,
                ),
            )->description('User ID')
                ->required()
                ->allowEmptyValue();

            expect($parameter->compile())->toBe([
                'name' => 'user',
                'in' => 'path',
                'description' => 'User ID',
                'required' => true,
                'allowEmptyValue' => true,
                'schema' => [
                    'type' => 'string',
                ],
                ...$expected,
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
            array $expected,
        ): void {
            $parameter = Parameter::query(
                'user',
                QueryParameter::create(
                    Schema::array(),
                    $style,
                ),
            )->description('User ID')
                ->required()
                ->deprecated();

            expect($parameter->compile())->toBe([
                'name' => 'user',
                'in' => 'query',
                'description' => 'User ID',
                'required' => true,
                'deprecated' => true,
                'schema' => [
                    'type' => 'array',
                ],
                ...$expected,
            ]);
        },
    )->with([
        'deepObject' => [
            DeepObject::create()->explode()->allowReserved(),
            [
                'style' => 'deepObject',
                'explode' => true,
                'allowReserved' => true,
            ],
        ],
        'form' => [
            Form::create(),
            [
                'style' => 'form',
            ],
        ],
        'pipeDelimited' => [
            PipeDelimited::create()->allowReserved(),
            [
                'style' => 'pipeDelimited',
                'allowReserved' => true,
            ],
        ],
        'spaceDelimited' => [
            SpaceDelimited::create()->explode(),
            [
                'style' => 'spaceDelimited',
                'explode' => true,
            ],
        ],
        'null' => [
            null,
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
})->covers(Parameter::class);
