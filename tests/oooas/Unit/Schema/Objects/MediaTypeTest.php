<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Encoding;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Example\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\Fields\Encoding\EncodingEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

describe(class_basename(MediaType::class), function (): void {
    it('can be created with no parameters', function (): void {
        $mediaType = MediaType::create();

        expect($mediaType->asArray())->toBeEmpty();
    });

    it('can be created with all parameters', function (): void {
        $mediaType = MediaType::create()
            ->schema(Schema::object())
            ->examples(Example::create('ExampleName'), Example::create('ExampleName2'))
            // TODO: Allow creating a Example without a key.
            // Sometimes examples are not named.
            // For example, when there is only one example.
            ->example(Example::create('ExampleName'))
            ->encoding(
                EncodingEntry::create(
                    'EncodingName',
                    Encoding::create(),
                ),
            );

        expect($mediaType->asArray())->toBe([
            'schema' => [
                'type' => 'object',
            ],
            'example' => [],
            'examples' => [
                'ExampleName' => [],
                'ExampleName2' => [],
            ],
            'encoding' => [
                'EncodingName' => [],
            ],
        ]);
    });
})->covers(MediaType::class);
