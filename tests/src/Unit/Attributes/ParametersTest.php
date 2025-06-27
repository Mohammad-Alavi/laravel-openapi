<?php

use MohammadAlavi\LaravelOpenApi\Attributes\Parameters;
use Tests\src\Support\Doubles\Stubs\Attributes\TestParameterFactory;
use Tests\src\Support\Doubles\Stubs\Attributes\TestParametersFactoryInvalid;

describe(class_basename(Parameters::class), function (): void {
    it('can set valid factory', function (): void {
        $parameters = new Parameters(factory: TestParameterFactory::class);
        expect($parameters->factory)->toBe(TestParameterFactory::class);
    });

    it('can handle invalid factory', function (): void {
        expect(function (): void {
            new Parameters(factory: TestParametersFactoryInvalid::class);
        })->toThrow(InvalidArgumentException::class);
    });

    it('can handle none existing factory', function (): void {
        expect(function (): void {
            new Parameters(factory: 'NonExistentFactory');
        })->toThrow(InvalidArgumentException::class);
    });
})->covers(Parameters::class);
