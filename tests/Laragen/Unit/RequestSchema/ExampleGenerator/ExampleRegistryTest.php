<?php

use MohammadAlavi\Laragen\RequestSchema\ExampleGenerator\Date;
use MohammadAlavi\Laragen\RequestSchema\ExampleGenerator\Email;
use MohammadAlavi\Laragen\RequestSchema\ExampleGenerator\ExampleRegistry;

describe(class_basename(ExampleRegistry::class), function (): void {
    it('registers and retrieves examples by rule name', function (): void {
        $registry = new ExampleRegistry([
            Date::rule() => Date::class,
            Email::rule() => Email::class,
        ]);

        expect($registry->has('date'))->toBeTrue()
            ->and($registry->has('email'))->toBeTrue()
            ->and($registry->get('date'))->toBe(Date::class)
            ->and($registry->get('email'))->toBe(Email::class);
    });

    it('returns null for unregistered rules', function (): void {
        $registry = new ExampleRegistry([]);

        expect($registry->has('unknown'))->toBeFalse()
            ->and($registry->get('unknown'))->toBeNull();
    });
})->covers(ExampleRegistry::class);
