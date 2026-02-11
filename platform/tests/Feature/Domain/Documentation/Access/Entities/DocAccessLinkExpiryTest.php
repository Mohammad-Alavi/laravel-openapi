<?php

declare(strict_types=1);

use App\Domain\Documentation\Access\Entities\DocAccessLink;
use App\Domain\Documentation\Access\ValueObjects\HashedToken;

describe(class_basename(DocAccessLink::class) . ' expiry', function (): void {
    it('is expired when expiry is in the past', function (): void {
        $link = new DocAccessLink();
        $link->forceFill([
            'project_id' => 1,
            'doc_role_id' => 1,
            'name' => 'Expired Link',
            'token' => HashedToken::fromPlain('test-token')->toString(),
            'expires_at' => now()->subDay(),
        ]);

        expect($link->isExpired())->toBeTrue()
            ->and($link->isValid())->toBeFalse();
    });

    it('is not expired when expiry is in the future', function (): void {
        $link = new DocAccessLink();
        $link->forceFill([
            'project_id' => 1,
            'doc_role_id' => 1,
            'name' => 'Future Link',
            'token' => HashedToken::fromPlain('test-token')->toString(),
            'expires_at' => now()->addDay(),
        ]);

        expect($link->isExpired())->toBeFalse()
            ->and($link->isValid())->toBeTrue();
    });
})->covers(DocAccessLink::class);
