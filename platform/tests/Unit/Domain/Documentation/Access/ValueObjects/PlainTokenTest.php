<?php

use App\Domain\Documentation\Access\ValueObjects\HashedToken;
use App\Domain\Documentation\Access\ValueObjects\PlainToken;

describe(class_basename(PlainToken::class), function (): void {
    it('generates a random token', function (): void {
        $token = PlainToken::generate();

        expect($token->toString())->toHaveLength(64);
    });

    it('generates unique tokens', function (): void {
        $token1 = PlainToken::generate();
        $token2 = PlainToken::generate();

        expect($token1->toString())->not->toBe($token2->toString());
    });

    it('produces a hashed version', function (): void {
        $token = PlainToken::generate();
        $hashed = $token->hashed();

        expect($hashed)->toBeInstanceOf(HashedToken::class)
            ->and($hashed->equals($token->toString()))->toBeTrue();
    });
})->covers(PlainToken::class);
