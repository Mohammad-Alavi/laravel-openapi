<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles\DeepObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles\Form;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles\Label;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles\Matrix;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles\PipeDelimited;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles\Simple;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles\SpaceDelimited;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\ContentSerialized;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedCookie;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedHeader;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedPath;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedQuery;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\ExampleEntry;

describe('Parameter', function (): void {
    it(
        'can create cookie parameter',
        function (
            Form|null $style,
            array $expected,
        ): void {
            $parameter = Parameter::cookie(
                'user',
                SchemaSerializedCookie::create(
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

            expect($parameter->unserializeToArray())->toBe([
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
                SchemaSerializedHeader::create(
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

            expect($parameter->unserializeToArray())->toBe([
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
                SchemaSerializedPath::create(
                    Schema::string(),
                    $style,
                ),
            )->description('User ID')
                ->required()
                ->allowEmptyValue();

            expect($parameter->unserializeToArray())->toBe([
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
                SchemaSerializedQuery::create(
                    Schema::array(),
                    $style,
                ),
            )->description('User ID')
                ->required()
                ->deprecated();

            expect($parameter->unserializeToArray())->toBe([
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
        function (ContentSerialized $contentSerialized, array $expected): void {
            $parameter = Parameter::query(
                'user',
                $contentSerialized,
            )->description('User ID')
                ->required()
                ->deprecated();

            expect($parameter->unserializeToArray())->toBe([
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
            ContentSerialized::create(
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
