<?php

use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Collectors\CollectionLocator;
use MohammadAlavi\LaravelOpenApi\Collectors\Components\ResponseCollector;
use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\Response;

describe('ResponseCollector', function (): void {
    beforeEach(function (): void {
        $locator = new CollectionLocator([__DIR__ . '/../../../Doubles/Stubs/Collectors/Components/Response']);
        $this->collector = new ResponseCollector($locator);
    });

    it('can collect response factories with default collection', function (): void {
        $result = $this->collector->collect();

        expect($result)->toBeInstanceOf(Collection::class)
            ->and($result->count())->toBe(2)
            ->and($result->first())->toBeInstanceOf(Response::class);
    });

    it('can collect response factories with specified collection', function (): void {
        $result = $this->collector->collect('test');

        expect($result)->toBeInstanceOf(Collection::class)
            ->and($result->count())->toBe(2)
            ->and($result->first())->toBeInstanceOf(Response::class);
    });

    it('returns empty collection when no factories found', function (): void {
        $locator = new CollectionLocator([]);
        $collector = new ResponseCollector($locator);

        $result = $collector->collect();

        expect($result)->toBeInstanceOf(Collection::class)
            ->and($result->isEmpty())->toBeTrue();
    });
})->covers(ResponseCollector::class);
