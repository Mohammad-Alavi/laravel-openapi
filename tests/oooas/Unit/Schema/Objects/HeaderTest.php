<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Header;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\ExampleEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles\Simple;
use Webmozart\Assert\InvalidArgumentException;

describe(class_basename(Header::class), function (): void {
    it('can be created with all parameters', function (): void {
        $header = Header::create()
            ->description('Lorem ipsum')
            ->required()
            ->deprecated()
            ->schema(Schema::object())
            ->style(Simple::create()->explode())
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

        $response = $header->compile();

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

    it('can be created with singular example', function (): void {
        $header = Header::create()
            ->schema(Schema::string())
            ->example('Bearer token123');

        expect($header->compile())->toBe([
            'schema' => [
                'type' => 'string',
            ],
            'example' => 'Bearer token123',
        ]);
    });

    it('can be created with complex example value', function (): void {
        $header = Header::create()
            ->schema(Schema::integer())
            ->example(42);

        expect($header->compile())->toBe([
            'schema' => [
                'type' => 'integer',
            ],
            'example' => 42,
        ]);
    });

    it('throws exception when setting example after examples', function (): void {
        $header = Header::create()
            ->examples(
                ExampleEntry::create('ExampleName', Example::create()),
            );

        expect(fn () => $header->example('test'))
            ->toThrow(InvalidArgumentException::class, 'example and examples fields are mutually exclusive.');
    });

    it('throws exception when setting examples after example', function (): void {
        $header = Header::create()
            ->example('test value');

        expect(fn () => $header->examples(
            ExampleEntry::create('ExampleName', Example::create()),
        ))->toThrow(InvalidArgumentException::class, 'examples and example fields are mutually exclusive.');
    });
})->covers(Header::class);
