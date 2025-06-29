<?php

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Support\ComponentCollector;
use Pest\Expectation;

describe(class_basename(ComponentCollector::class), function (): void {
    it('can collect specific collections', function (): void {
        $sut = new ComponentCollector([
            __DIR__ . '/../../Support/Doubles/Stubs/Builders/Components',
        ]);

        $result = $sut->collect('test')->map(static fn ($component) => $component::class);

        expect($result)->toHaveCount(18)
            ->each(function (Expectation $expectation) {
                return $expectation->toHaveAttribute(Collection::class);
            });
    });
})->covers(ComponentCollector::class);
