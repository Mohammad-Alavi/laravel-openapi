<?php

declare(strict_types=1);

use App\Domain\Documentation\Access\Entities\DocAccessLink;
use App\Domain\Documentation\Access\ValueObjects\HashedToken;

describe(class_basename(DocAccessLink::class), function (): void {
    it('is not expired when no expiry set', function (): void {
        $link = new DocAccessLink();
        $link->forceFill([
            'project_id' => 1,
            'doc_role_id' => 1,
            'name' => 'Partner Link',
            'token' => HashedToken::fromPlain('test-token')->toString(),
            'expires_at' => null,
        ]);

        expect($link->isExpired())->toBeFalse()
            ->and($link->isValid())->toBeTrue();
    });

    it('verifies correct plain token', function (): void {
        $hashed = HashedToken::fromPlain('my-secret');
        $link = new DocAccessLink();
        $link->forceFill([
            'project_id' => 1,
            'doc_role_id' => 1,
            'name' => 'Test',
            'token' => $hashed->toString(),
        ]);

        expect($link->verifyToken('my-secret'))->toBeTrue()
            ->and($link->verifyToken('wrong-token'))->toBeFalse();
    });

    it('exposes domain properties via contract methods', function (): void {
        $link = new DocAccessLink();
        $link->forceFill([
            'id' => 5,
            'project_id' => 42,
            'doc_role_id' => 3,
            'name' => 'Test Link',
            'token' => 'some-hash',
            'expires_at' => null,
            'last_used_at' => null,
        ]);

        expect($link->getId())->toBe(5)
            ->and($link->getProjectId())->toBe(42)
            ->and($link->getDocRoleId())->toBe(3)
            ->and($link->getTokenHash())->toBe('some-hash')
            ->and($link->getExpiresAt())->toBeNull()
            ->and($link->getLastUsedAt())->toBeNull();
    });
})->covers(DocAccessLink::class);
