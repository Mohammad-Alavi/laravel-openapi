<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Sources\BodyReference;

describe('BodyReference', function (): void {
    it('can be created with no JSON pointer', function (): void {
        $bodyReference = BodyReference::create();

        expect($bodyReference->jsonPointer())->toBe('');
        expect($bodyReference->toString())->toBe('body');
    });

    it('can be created with a valid JSON pointer', function (): void {
        $bodyReference = BodyReference::create('/user/id');

        expect($bodyReference->jsonPointer())->toBe('/user/id');
        expect($bodyReference->toString())->toBe('body#/user/id');
    });

    it('can be created with a JSON pointer containing escaped characters', function (): void {
        $bodyReference = BodyReference::create('/user/~0name/~1path');

        expect($bodyReference->jsonPointer())->toBe('/user/~0name/~1path');
        expect($bodyReference->toString())->toBe('body#/user/~0name/~1path');
    });

    it('throws an exception for an invalid JSON pointer', function (): void {
        expect(fn (): BodyReference => BodyReference::create('invalid'))->toThrow(
            InvalidArgumentException::class,
            'JSON pointer must start with "/", got "invalid"',
        );
    });

    it('throws an exception for a JSON pointer with invalid escape sequence', function (): void {
        expect(fn (): BodyReference => BodyReference::create('/user/~2name'))->toThrow(
            InvalidArgumentException::class,
            'JSON pointer contains invalid escape sequence: "/user/~2name"',
        );
    });
})->covers(BodyReference::class);
