<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Extensions\Extension;

describe('Extension', function (): void {
    it('can create extension with mixed value', function (mixed $value): void {
        $extension = Extension::create('x-test', $value);

        expect($extension->name())->toBe('x-test')
            ->and($extension->value())->toBe($value);
    })->with([
        'null' => [null],
        'string' => ['test'],
        'array' => [['test']],
        'object' => [new stdClass()],
        'integer' => [1],
        'float' => [1.1],
        'boolean' => [true],
        'empty string' => [''],
        'empty array' => [[]],
    ]);

    it('can guess if a string is an extension', function (): void {
        expect(Extension::isExtension('x-test'))->toBeTrue()
            ->and(Extension::isExtension('test'))->toBeFalse();
    });

    it('throws exception if extension name is invalid', function (string $name, string $message): void {
        expect(fn (): Extension => Extension::create($name, 'value'))->toThrow(
            InvalidArgumentException::class,
            $message,
        );
    })->with([
        'x-oai-' => ['x-oai-', 'Extension name cannot be x-oai-'],
        'x-oas-' => ['x-oas-', 'Extension name cannot be x-oas-'],
        'test' => ['test', 'Extension name must start with x-'],
    ]);

    it('can serialize extension', function (): void {
        $extension = Extension::create('x-test', 'value');

        $result = $extension->jsonSerialize();

        expect($result)->toBe(['x-test' => 'value']);
    });

    it('can compare extensions', function (): void {
        $extension1 = Extension::create('x-test', 'value');
        $extension2 = Extension::create('x-test', 'value');

        $result = $extension1->equals($extension2);

        expect($result)->toBeTrue();
    });

    it('can json serialize extension', function (): void {
        $extension = Extension::create('x-test', 'value');

        $result = json_encode($extension);

        expect($result)->toBe('{"x-test":"value"}');
    });
})->covers(Extension::class);
