<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Encoding\Encoding;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\Fields\Encoding\EncodingEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\ExampleEntry;
use Webmozart\Assert\InvalidArgumentException;

describe(class_basename(MediaType::class), function (): void {
    it('can be created with no parameters', function (): void {
        $mediaType = MediaType::create();

        expect($mediaType->compile())->toBeEmpty();
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

        expect($mediaType->compile())->toBe([
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

    it('can be created with singular example', function (): void {
        $mediaType = MediaType::create()
            ->schema(Schema::string())
            ->example('hello world');

        expect($mediaType->compile())->toBe([
            'schema' => [
                'type' => 'string',
            ],
            'example' => 'hello world',
        ]);
    });

    it('can be created with complex example value', function (): void {
        $mediaType = MediaType::create()
            ->schema(Schema::object())
            ->example(['name' => 'John', 'age' => 30]);

        expect($mediaType->compile())->toBe([
            'schema' => [
                'type' => 'object',
            ],
            'example' => ['name' => 'John', 'age' => 30],
        ]);
    });

    it('throws exception when setting example after examples', function (): void {
        $mediaType = MediaType::create()
            ->examples(
                ExampleEntry::create('ExampleName', Example::create()),
            );

        expect(fn () => $mediaType->example('test'))
            ->toThrow(InvalidArgumentException::class, 'example and examples fields are mutually exclusive.');
    });

    it('throws exception when setting examples after example', function (): void {
        $mediaType = MediaType::create()
            ->example('test value');

        expect(fn () => $mediaType->examples(
            ExampleEntry::create('ExampleName', Example::create()),
        ))->toThrow(InvalidArgumentException::class, 'examples and example fields are mutually exclusive.');
    });
})->covers(MediaType::class);
