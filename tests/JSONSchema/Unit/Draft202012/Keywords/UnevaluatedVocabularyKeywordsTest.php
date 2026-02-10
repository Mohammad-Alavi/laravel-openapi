<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\UnevaluatedItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\UnevaluatedProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(UnevaluatedItems::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = UnevaluatedItems::create(
            LooseFluentDescriptor::withoutSchema()->type(Type::string()),
        );

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(UnevaluatedItems::name())->toBe('unevaluatedItems');
    });

    it('returns value correctly', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()->type(Type::string());
        $keyword = UnevaluatedItems::create($schema);

        expect($keyword->value())->toBe($schema);
    });

    it('serializes as schema', function (): void {
        $keyword = UnevaluatedItems::create(
            LooseFluentDescriptor::withoutSchema()->type(Type::integer())->minimum(0),
        );

        $serialized = json_decode(json_encode($keyword), true);

        expect($serialized['type'])->toBe('integer');
        expect($serialized['minimum'])->toBe(0);
    });

    it('can disallow unevaluated items with false schema', function (): void {
        $keyword = UnevaluatedItems::create(
            LooseFluentDescriptor::withoutSchema(),
        );

        // Empty schema compiles to empty object, which matches nothing when used as unevaluatedItems
        $serialized = json_decode(json_encode($keyword), true);

        expect($serialized)->toBeArray();
    });
})->covers(UnevaluatedItems::class);

describe(class_basename(UnevaluatedProperties::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = UnevaluatedProperties::create(
            LooseFluentDescriptor::withoutSchema()->type(Type::string()),
        );

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(UnevaluatedProperties::name())->toBe('unevaluatedProperties');
    });

    it('returns value correctly', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()->type(Type::boolean());
        $keyword = UnevaluatedProperties::create($schema);

        expect($keyword->value())->toBe($schema);
    });

    it('serializes as schema', function (): void {
        $keyword = UnevaluatedProperties::create(
            LooseFluentDescriptor::withoutSchema()->type(Type::string())->maxLength(100),
        );

        $serialized = json_decode(json_encode($keyword), true);

        expect($serialized['type'])->toBe('string');
        expect($serialized['maxLength'])->toBe(100);
    });
})->covers(UnevaluatedProperties::class);
