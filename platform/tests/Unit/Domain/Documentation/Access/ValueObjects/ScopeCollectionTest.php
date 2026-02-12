<?php

use App\Domain\Documentation\Access\ValueObjects\Scope;
use App\Domain\Documentation\Access\ValueObjects\ScopeCollection;

describe(class_basename(ScopeCollection::class), function (): void {
    it('matches any scope in collection', function (): void {
        $collection = ScopeCollection::fromArray(['payments', 'webhooks']);

        expect($collection->matchesAny('payments'))->toBeTrue()
            ->and($collection->matchesAny('webhooks'))->toBeTrue()
            ->and($collection->matchesAny('users'))->toBeFalse();
    });

    it('supports wildcard patterns', function (): void {
        $collection = ScopeCollection::fromArray(['payments.*', '/api/v2/*']);

        expect($collection->matchesAny('payments.refunds'))->toBeTrue()
            ->and($collection->matchesAny('/api/v2/users'))->toBeTrue()
            ->and($collection->matchesAny('users'))->toBeFalse();
    });

    it('returns false for empty collection', function (): void {
        $collection = new ScopeCollection();

        expect($collection->matchesAny('anything'))->toBeFalse()
            ->and($collection->isEmpty())->toBeTrue();
    });

    it('counts scopes', function (): void {
        expect(ScopeCollection::fromArray(['a', 'b', 'c'])->count())->toBe(3)
            ->and((new ScopeCollection())->count())->toBe(0);
    });

    it('detects wildcards', function (): void {
        expect(ScopeCollection::fromArray(['payments.*'])->hasWildcards())->toBeTrue()
            ->and(ScopeCollection::fromArray(['payments'])->hasWildcards())->toBeFalse();
    });

    it('converts to array of strings', function (): void {
        $collection = ScopeCollection::fromArray(['payments', 'webhooks']);

        expect($collection->toArray())->toBe(['payments', 'webhooks']);
    });

    it('can be constructed from Scope objects', function (): void {
        $collection = new ScopeCollection([new Scope('payments'), new Scope('webhooks')]);

        expect($collection->count())->toBe(2)
            ->and($collection->matchesAny('payments'))->toBeTrue();
    });
})->covers(ScopeCollection::class);
