<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Encoding\Encoding;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\Fields\Encoding\EncodingEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\ExampleEntry;

describe(class_basename(MediaType::class), function (): void {
    it('can be created with no parameters', function (): void {
        $mediaType = MediaType::create();

        expect($mediaType->asArray())->toBeEmpty();
    });

    it('can be created with all parameters', function (): void {
        $mediaType = MediaType::create()
            ->schema(Schema::object())
            ->examples(
                ExampleEntry::create(
                    'ExampleName',
                    Example::create(),
                ),
                ExampleEntry::create(
                    'ExampleName2',
                    Example::create(),
                ),
            )->encoding(
                EncodingEntry::create(
                    'EncodingName',
                    Encoding::create(),
                ),
            );

        expect($mediaType->asArray())->toBe([
            'schema' => [
                'type' => 'object',
            ],
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
