<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Header;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\ExampleEntry;

describe(class_basename(Header::class), function (): void {
    it('can be created with all parameters', function (): void {
        $header = Header::create()
            ->description(Description::create('Lorem ipsum'))
            ->required()
            ->deprecated()
            ->schema(Schema::object())
            ->style('simple')
            ->explode()
            ->examples(
                ExampleEntry::create(
                    'ExampleName',
                    Example::create(),
                ),
            )
            ->content(
                ContentEntry::json(
                    MediaType::create(),
                ),
            );

        $response = $header->unserializeToArray();

        expect($response)->toBe([
            'description' => 'Lorem ipsum',
            'required' => true,
            'deprecated' => true,
            'style' => 'simple',
            'explode' => true,
            'schema' => [
                'type' => 'object',
            ],
            'examples' => [
                'ExampleName' => [],
            ],
            'content' => [
                'application/json' => [],
            ],
        ]);
    });
})->covers(Header::class);
