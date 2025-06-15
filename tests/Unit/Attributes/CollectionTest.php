<?php

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Generator;
use Tests\Doubles\Stubs\Attributes\TestStringable;

describe('Collection', function (): void {
    it('can fall back to default collection', function (): void {
        $collection = new Collection();
        expect($collection->name)->toBe([Generator::COLLECTION_DEFAULT]);
    });

    it('can accept string as collection name', function (): void {
        $collection = new Collection('collection');
        expect($collection->name)->toBe(['collection']);
    });

    it('can accept array of strings as collection name', function (): void {
        $collection = new Collection(['collection1', 'collection2']);
        expect($collection->name)->toBe(['collection1', 'collection2']);
    });

    it('can accept array of stringables as collection name', function (): void {
        $collection = new Collection([TestStringable::class]);
        expect($collection->name)->toBe(['stringable']);
    });

    it('can accept mixed array of strings and stringables as collection name', function (): void {
        $collection = new Collection(['collection', TestStringable::class]);
        expect($collection->name)->toBe(['collection', 'stringable']);
    });
})->covers(Collection::class);
