<?php

declare(strict_types=1);

use App\Domain\Documentation\Access\ValueObjects\HashedToken;

describe(class_basename(HashedToken::class), function (): void {
    it('creates from plain token', function (): void {
        $hashed = HashedToken::fromPlain('my-secret-token');

        expect($hashed->hash)->toBe(hash('sha256', 'my-secret-token'));
    });

    it('verifies correct plain token', function (): void {
        $hashed = HashedToken::fromPlain('my-secret-token');

        expect($hashed->equals('my-secret-token'))->toBeTrue()
            ->and($hashed->equals('wrong-token'))->toBeFalse();
    });

    it('converts to string', function (): void {
        $hashed = HashedToken::fromPlain('my-secret-token');

        expect($hashed->toString())->toBe(hash('sha256', 'my-secret-token'));
    });

    it('rejects empty hash', function (): void {
        new HashedToken('');
    })->throws(InvalidArgumentException::class);
})->covers(HashedToken::class);
