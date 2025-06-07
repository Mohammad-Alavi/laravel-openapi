<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles\Simple;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedPath;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

describe('Parameter', function (): void {
    it('can be created with all parameters', function (): void {
        $parameter = Parameter::path(
            Name::create('user'),
            SchemaSerializedPath::create(
                Schema::string(),
                Simple::create()->explode(),
                Example::create('example_test'),
                Example::create('ExampleName'),
            ),
        )->description(Description::create('User ID'))
            ->required()
            ->deprecated()
            ->allowEmptyValue();

        expect($parameter->asArray())->toBe([
            'name' => 'user',
            'in' => 'path',
            'description' => 'User ID',
            'required' => true,
            'deprecated' => true,
            'allowEmptyValue' => true,
            'schema' => [
                'type' => 'string',
            ],
            'style' => 'simple',
            'explode' => true,
            'example' => [],
            'examples' => [
                'ExampleName' => [],
            ],
        ]);
    });

    //    it('can be created with all combinations', function (string $method, string $expectedType): void {
    //        $parameter = Parameter::$method();
    //
    //        expect($parameter->in)->toBe($expectedType);
    //    })->with([
    //        'query' => ['query', Parameter::IN_QUERY],
    //        'header' => ['header', Parameter::IN_HEADER],
    //        'path' => ['path', Parameter::IN_PATH],
    //        'cookie' => ['cookie', Parameter::IN_COOKIE],
    //    ])->with([
    //        'style matrix' => ['style', Parameter::STYLE_MATRIX],
    //        'style label' => ['label', Parameter::STYLE_LABEL],
    //        'style form' => ['form', Parameter::STYLE_FORM],
    //        'style simple' => ['simple', Parameter::STYLE_SIMPLE],
    //        'style spaceDelimited' => ['spaceDelimited', Parameter::STYLE_SPACE_DELIMITED],
    //        'style pipeDelimited' => ['pipeDelimited', Parameter::STYLE_PIPE_DELIMITED],
    //        'style deepObject' => ['deepObject', Parameter::STYLE_DEEP_OBJECT],
    //    ]);
})->covers(Parameter::class);
