<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\In\In;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

describe('Parameter', function (): void {
    it('can be created with all parameters', function (): void {
        $parameter = Parameter::schema(Name::create('user'), In::path(), Schema::string())
            ->description(Description::create('User ID'))
            ->required()
            ->deprecated()
            ->allowEmptyValue()
            ->style(Parameter::STYLE_SIMPLE)
            ->explode()
            ->allowReserved()
            ->example(Example::create('example_test'))
            ->examples(Example::create('ExampleName'))
            ->content(MediaType::json());

        expect($parameter->asArray())->toBe([
            'name' => 'user',
            'in' => 'path',
            'description' => 'User ID',
            'required' => true,
            'deprecated' => true,
            'allowEmptyValue' => true,
            'style' => 'simple',
            'explode' => true,
            'allowReserved' => true,
            'schema' => [
                'type' => 'string',
            ],
            'example' => [],
            'examples' => [
                'ExampleName' => [],
            ],
            'content' => [
                'application/json' => [],
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
