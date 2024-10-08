<?php

use Pest\Arch\Contracts\ArchExpectation;
use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Collectors\CollectionLocator;
use Pest\Expectation;
use Tests\Doubles\Stubs\Collectors\Components\PathMiddlewareStub;

describe('CollectionLocator', function (): void {
    it('can collect specific collections', function (): void {
        $locator = new CollectionLocator([
            __DIR__ . '/../../Doubles/Stubs/Collectors/Components',
        ]);

        $result = $locator->find('test');

        expect($result)->toHaveCount(10)
            ->and($result)->each(fn (Expectation $expectation): ArchExpectation => $expectation->toUse(Collection::class));
    });

    it('collects default collection if no collection passed', function (): void {
        $locator = new CollectionLocator([
            __DIR__ . '/../../Doubles/Stubs/Collectors/Components',
        ]);

        $result = $locator->find();

        expect($result)->toHaveCount(11)
            ->and($result)
            ->each(
                fn (Expectation $expectation): Expectation => $expectation
                ->unless(
                    static fn (): bool => str_contains((string) $expectation->value, 'Implicit')
                        || PathMiddlewareStub::class === $expectation->value,
                    fn (Expectation $expectation): ArchExpectation => $expectation->toUse(Collection::class),
                ),
            );
    });
})->covers(CollectionLocator::class);
