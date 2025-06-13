<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect\Draft202012;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Format;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Id;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MaxLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MinLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Pattern;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Properties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Schema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;

describe(class_basename(Draft202012::class), function (): void {
    it('can create id keyword', function (): void {
        $id = Draft202012::id('https://example.com/schema.json');

        expect($id)->toBeInstanceOf(Id::class)
            ->and($id->value())->toBe('https://example.com/schema.json');
    });

    it('can create schema keyword', function (): void {
        $schema = Draft202012::schema('https://json-schema.org/draft/2020-12/schema');

        expect($schema)->toBeInstanceOf(Schema::class)
            ->and($schema->value())->toBe('https://json-schema.org/draft/2020-12/schema');
    });

    it('can create type keyword', function (): void {
        $type = Draft202012::type('string');

        expect($type)->toBeInstanceOf(Type::class)
            ->and($type->value())->toBe('string');
    });

    it('can create format keyword', function (): void {
        $format = Draft202012::format(StringFormat::DATE);

        expect($format)->toBeInstanceOf(Format::class)
            ->and($format->value())->toBe('date');
    });

    it('can create minLength keyword', function (): void {
        $minLength = Draft202012::minLength(5);

        expect($minLength)->toBeInstanceOf(MinLength::class)
            ->and($minLength->value())->toBe(5);
    });

    it('can create maxLength keyword', function (): void {
        $maxLength = Draft202012::maxLength(10);

        expect($maxLength)->toBeInstanceOf(MaxLength::class)
            ->and($maxLength->value())->toBe(10);
    });

    it('can create pattern keyword', function (): void {
        $pattern = Draft202012::pattern('^[a-zA-Z0-9]*$');

        expect($pattern)->toBeInstanceOf(Pattern::class)
            ->and($pattern->value())->toBe('^[a-zA-Z0-9]*$');
    });

    it('can create properties keyword', function (): void {
        $mockDescriptor = Descriptor::withoutSchema();
        $property = Property::create('name', $mockDescriptor);
        $properties = Draft202012::properties($property);

        expect($properties)->toBeInstanceOf(Properties::class)
            ->and(\Safe\json_encode($properties))->toBe(\Safe\json_encode(['name' => $mockDescriptor]));
    });
})->covers(Draft202012::class);
