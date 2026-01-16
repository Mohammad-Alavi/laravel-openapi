<?php

namespace Tests\oooas\Unit\Schema\Objects\XML;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\XML\Fields\XmlNamespace;

describe(class_basename(XmlNamespace::class), function (): void {
    it('can create with valid absolute URI', function (): void {
        $namespace = XmlNamespace::create('http://example.com/schema');

        expect($namespace->value())->toBe('http://example.com/schema')
            ->and($namespace->jsonSerialize())->toBe('http://example.com/schema');
    });

    it('accepts https URIs', function (): void {
        $namespace = XmlNamespace::create('https://www.w3.org/2001/XMLSchema');

        expect($namespace->value())->toBe('https://www.w3.org/2001/XMLSchema');
    });

    it('accepts URN format', function (): void {
        $namespace = XmlNamespace::create('urn:example:namespace');

        expect($namespace->value())->toBe('urn:example:namespace');
    });

    it('rejects empty namespace', function (): void {
        expect(fn () => XmlNamespace::create(''))->toThrow(\InvalidArgumentException::class);
    });

    it('rejects relative URI without scheme', function (): void {
        expect(fn () => XmlNamespace::create('/path/to/schema'))->toThrow(\InvalidArgumentException::class)
            ->and(fn () => XmlNamespace::create('example.com/schema'))->toThrow(\InvalidArgumentException::class);
    });
})->covers(XmlNamespace::class);
