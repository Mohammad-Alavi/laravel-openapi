<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Keyword as BaseKeyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword as Draft202012Keyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Maximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;

describe('Keyword Contract Hierarchy', function (): void {
    it('Draft202012 Keyword extends base Keyword', function (): void {
        $reflection = new ReflectionClass(Draft202012Keyword::class);
        $interfaces = $reflection->getInterfaceNames();

        expect($interfaces)->toContain(BaseKeyword::class);
    });

    it('Maximum implements Draft202012 Keyword', function (): void {
        $maximum = Maximum::create(100);

        expect($maximum)->toBeInstanceOf(Draft202012Keyword::class);
        expect($maximum)->toBeInstanceOf(BaseKeyword::class);
    });

    it('Type implements Draft202012 Keyword', function (): void {
        $type = Type::create('string');

        expect($type)->toBeInstanceOf(Draft202012Keyword::class);
        expect($type)->toBeInstanceOf(BaseKeyword::class);
    });

    it('base Keyword requires name() method', function (): void {
        $reflection = new ReflectionClass(BaseKeyword::class);

        expect($reflection->hasMethod('name'))->toBeTrue();
        expect($reflection->getMethod('name')->isStatic())->toBeTrue();
    });

    it('base Keyword requires value() method', function (): void {
        $reflection = new ReflectionClass(BaseKeyword::class);

        expect($reflection->hasMethod('value'))->toBeTrue();
    });

    it('base Keyword extends JsonSerializable', function (): void {
        $reflection = new ReflectionClass(BaseKeyword::class);
        $interfaces = $reflection->getInterfaceNames();

        expect($interfaces)->toContain(JsonSerializable::class);
    });
})->covers(Draft202012Keyword::class);
