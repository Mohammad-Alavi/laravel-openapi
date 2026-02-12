<?php

use App\Domain\Documentation\Access\ValueObjects\Scope;

describe(class_basename(Scope::class), function (): void {
    it('matches exact string', function (): void {
        $scope = new Scope('payments');

        expect($scope->matches('payments'))->toBeTrue()
            ->and($scope->matches('users'))->toBeFalse();
    });

    it('matches wildcard suffix', function (): void {
        $scope = new Scope('payments.*');

        expect($scope->matches('payments.refunds'))->toBeTrue()
            ->and($scope->matches('payments.invoices'))->toBeTrue()
            ->and($scope->matches('payments'))->toBeFalse()
            ->and($scope->matches('users'))->toBeFalse();
    });

    it('matches wildcard prefix', function (): void {
        $scope = new Scope('/api/v2/*');

        expect($scope->matches('/api/v2/users'))->toBeTrue()
            ->and($scope->matches('/api/v2/orders'))->toBeTrue()
            ->and($scope->matches('/api/v1/users'))->toBeFalse();
    });

    it('matches wildcard in middle', function (): void {
        $scope = new Scope('/api/*/admin');

        expect($scope->matches('/api/v1/admin'))->toBeTrue()
            ->and($scope->matches('/api/v2/admin'))->toBeTrue()
            ->and($scope->matches('/api/v1/users'))->toBeFalse();
    });

    it('matches everything with star', function (): void {
        $scope = new Scope('*');

        expect($scope->matches('anything'))->toBeTrue()
            ->and($scope->matches('/api/v1/users'))->toBeTrue();
    });

    it('detects wildcard presence', function (): void {
        expect((new Scope('payments.*'))->hasWildcard())->toBeTrue()
            ->and((new Scope('payments'))->hasWildcard())->toBeFalse();
    });

    it('converts to string', function (): void {
        expect((new Scope('payments'))->toString())->toBe('payments');
    });

    it('rejects empty pattern', function (): void {
        new Scope('');
    })->throws(InvalidArgumentException::class);
})->covers(Scope::class);
