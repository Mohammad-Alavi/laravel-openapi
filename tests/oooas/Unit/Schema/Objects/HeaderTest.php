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
    it('can be created with schema-based serialization', function (): void {
        $header = Header::create()
            ->description('Lorem ipsum')
            ->required()
            ->deprecated()
            ->schema(Schema::object(), Simple::create()->explode())
            ->examples(
                ExampleEntry::create(
                    'ExampleName',
                    Example::create(),
                ),
            );

        expect($header->compile())->toBe([
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
        ]);
    });

    it('can be created with content-based serialization', function (): void {
        $header = Header::create()
            ->description('Lorem ipsum')
            ->content(
                ContentEntry::json(
                    MediaType::create(),
                ),
            );

        expect($header->compile())->toBe([
            'description' => 'Lorem ipsum',
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

    it('can be created with allowReserved (OAS 3.2)', function (): void {
        $header = Header::create()
            ->schema(Schema::string())
            ->allowReserved();

        expect($header->compile())->toBe([
            'allowReserved' => true,
            'schema' => [
                'type' => 'string',
            ],
        ]);
    });

    it('can be created with style and allowReserved (OAS 3.2)', function (): void {
        $header = Header::create()
            ->schema(Schema::array(), Simple::create()->explode())
            ->allowReserved();

        expect($header->compile())->toBe([
            'style' => 'simple',
            'explode' => true,
            'allowReserved' => true,
            'schema' => [
                'type' => 'array',
            ],
        ]);
    });

    it('can be created without serialization rule', function (): void {
        $header = Header::create()
            ->description('A simple header')
            ->required();

        expect($header->compile())->toBe([
            'description' => 'A simple header',
            'required' => true,
        ]);
    });

    it('throws when setting content after schema', function (): void {
        $header = Header::create()
            ->schema(Schema::string());

        expect(fn () => $header->content(ContentEntry::json(MediaType::create())))
            ->toThrow(InvalidArgumentException::class, 'content and schema fields are mutually exclusive.');
    });

    it('throws when setting schema after content', function (): void {
        $header = Header::create()
            ->content(ContentEntry::json(MediaType::create()));

        expect(fn () => $header->schema(Schema::string()))
            ->toThrow(InvalidArgumentException::class, 'schema and content fields are mutually exclusive.');
    });

    it('can use example with content-based serialization', function (): void {
        $header = Header::create()
            ->content(
                ContentEntry::json(MediaType::create()),
            )
            ->example('some-value');

        expect($header->compile())->toBe([
            'example' => 'some-value',
            'content' => [
                'application/json' => [],
            ],
        ]);
    });

    it('can use examples with content-based serialization', function (): void {
        $header = Header::create()
            ->content(
                ContentEntry::json(MediaType::create()),
            )
            ->examples(
                ExampleEntry::create('first', Example::create()->value('a')),
                ExampleEntry::create('second', Example::create()->value('b')),
            );

        expect($header->compile())->toBe([
            'examples' => [
                'first' => ['value' => 'a'],
                'second' => ['value' => 'b'],
            ],
            'content' => [
                'application/json' => [],
            ],
        ]);
    });
})->covers(Header::class);
