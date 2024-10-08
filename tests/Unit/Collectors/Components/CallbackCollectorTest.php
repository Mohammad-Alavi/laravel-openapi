<?php

use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Collectors\CollectionLocator;
use MohammadAlavi\LaravelOpenApi\Collectors\Components\CallbackCollector;
use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\PathItem;

describe('CallbackCollector', function (): void {
    beforeEach(function (): void {
        $locator = new CollectionLocator([__DIR__ . '/../../../Doubles/Stubs/Collectors/Components/Callback']);
        $this->collector = new CallbackCollector($locator);
    });

    it('can collect callback factories with default collection', function (): void {
        $result = $this->collector->collect();

        expect($result)->toBeInstanceOf(Collection::class)
            ->and($result->count())->toBe(2)
            ->and($result->first())->toBeInstanceOf(PathItem::class);
    });

    it('can collect callback factories with specified collection', function (): void {
        $result = $this->collector->collect('test');

        expect($result)->toBeInstanceOf(Collection::class)
            ->and($result->count())->toBe(2)
            ->and($result->first())->toBeInstanceOf(PathItem::class);
    });

    it('returns empty collection when no factories found', function (): void {
        $locator = new CollectionLocator([]);
        $collector = new CallbackCollector($locator);

        $result = $collector->collect();

        expect($result)->toBeInstanceOf(Collection::class)
            ->and($result->isEmpty())->toBeTrue();
    });
})->covers(CallbackCollector::class);
