<?php

namespace Tests\oooas\Unit\Schema\Objects\PathItem\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Fields\Ref;
use Webmozart\Assert\InvalidArgumentException;

describe(class_basename(Ref::class), function (): void {
    it('can be created with local reference', function (): void {
        $ref = Ref::create('#/components/pathItems/UserPath');

        expect($ref->value())->toBe('#/components/pathItems/UserPath')
            ->and((string) $ref)->toBe('#/components/pathItems/UserPath')
            ->and($ref->jsonSerialize())->toBe('#/components/pathItems/UserPath');
    });

    it('can be created with external reference', function (): void {
        $ref = Ref::create('https://example.com/openapi.yaml#/components/pathItems/UserPath');

        expect($ref->value())->toBe('https://example.com/openapi.yaml#/components/pathItems/UserPath');
    });

    it('can be created with relative reference', function (): void {
        $ref = Ref::create('./paths/users.yaml');

        expect($ref->value())->toBe('./paths/users.yaml');
    });

    it('throws exception for invalid URI', function (): void {
        expect(fn () => Ref::create(''))
            ->toThrow(InvalidArgumentException::class);
    });
})->covers(Ref::class);
