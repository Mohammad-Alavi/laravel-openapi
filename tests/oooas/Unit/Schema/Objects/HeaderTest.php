<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Header;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\SharedFields\Content\ContentEntry;

describe(class_basename(Header::class), function (): void {
    it('can be created with all parameters', function (): void {
        $header = Header::create('HeaderName')
            ->description(Description::create('Lorem ipsum'))
            ->required()
            ->deprecated()
            ->schema(Schema::object())
            ->style('simple')
            ->explode()
            ->example('Example value')
            ->examples(Example::create('ExampleName'))
            ->content(
                ContentEntry::json(
                    MediaType::create(),
                ),
            );

        $response = $header->asArray();

        expect($response)->toBe([
            'description' => 'Lorem ipsum',
            'required' => true,
            'deprecated' => true,
            'style' => 'simple',
            'explode' => true,
            'schema' => [
                'type' => 'object',
            ],
            'example' => 'Example value',
            'examples' => [
                'ExampleName' => [],
            ],
            'content' => [
                'application/json' => [],
            ],
        ]);
    });
})->covers(Header::class);
