<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Extensions\Extension;
use MohammadAlavi\ObjectOrientedOpenAPI\Extensions\Extensions;
use Webmozart\Assert\InvalidArgumentException;

describe('Extensions', function (): void {
    it('checks if extension exists', function (): void {
        $extensions = Extensions::create();

        expect($extensions->has('test'))->toBeFalse();
    });

    it('checks if extension exists with x-', function (): void {
        $extensions = Extensions::create();

        expect($extensions->has('x-test'))->toBeFalse();
    });

    it('can get extension', function (): void {
        $extensions = Extensions::create();
        $extension = Extension::create('x-test', 'test');
        $extensions = $extensions->add($extension);

        $result = $extensions->get('x-test');

        expect($result)->toBe($extension);
    });

    it('sets extension', function (): void {
        $extensions = Extensions::create();
        $extension = Extension::create('x-test', 'wat');

        expect($extensions->all())->toBeEmpty();

        $result = $extensions->add($extension);

        expect($result->all())->toHaveCount(1)
            ->and($result->all()[0])->toBe($extension);
    });

    it('throws exception if extension does not exist', function (): void {
        $extensions = Extensions::create();

        expect(fn (): Extension => $extensions->get('x-test'))->toThrow(
            InvalidArgumentException::class,
            'Extension not found: x-test',
        );
    });

    it('can remove extension', function (): void {
        $extensions = Extensions::create();
        $extension = Extension::create('x-test', 'wat');
        $extensions = $extensions->add($extension);

        $result = $extensions->remove('x-test');

        expect($result->all())->toBeEmpty();
    });

    it('throws exception if extension does not exist when removing', function (): void {
        $extensions = Extensions::create();

        expect($extensions->has('nonexistent'))->toBeFalse()
            ->and(fn (): Extensions => $extensions->remove('nonexistent'))->toThrow(
                InvalidArgumentException::class,
                'Extension not found: nonexistent',
            );
    });

    it('can be serialized', function (): void {
        $extensions = Extensions::create();
        $extension = Extension::create('x-test', 'test');
        $extensions = $extensions->add($extension);

        expect($extensions->compile())->toBe(['x-test' => 'test'])
            ->and($extensions->compile())->toBe(['x-test' => 'test']);
    });

    it('can return all extensions', function (): void {
        $extensions = Extensions::create();
        $extension1 = Extension::create('x-test', 'wat');
        $extension2 = Extension::create('x-test2', 'wat');
        $extensions = $extensions->add($extension1);
        $extensions = $extensions->add($extension2);

        $result = $extensions->all();

        expect($result)->toBe([$extension1, $extension2]);
    });
})->covers(Extensions::class);
