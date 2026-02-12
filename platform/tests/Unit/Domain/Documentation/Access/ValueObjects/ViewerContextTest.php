<?php

use App\Domain\Documentation\Access\Contracts\DocRole;
use App\Domain\Documentation\Access\ValueObjects\ViewerContext;

describe(class_basename(ViewerContext::class), function (): void {
    it('creates owner context', function (): void {
        $context = ViewerContext::owner();

        expect($context->isOwner())->toBeTrue()
            ->and($context->isAnonymous())->toBeFalse()
            ->and($context->hasRole())->toBeFalse()
            ->and($context->role())->toBeNull();
    });

    it('creates anonymous context', function (): void {
        $context = ViewerContext::anonymous();

        expect($context->isOwner())->toBeFalse()
            ->and($context->isAnonymous())->toBeTrue()
            ->and($context->hasRole())->toBeFalse()
            ->and($context->role())->toBeNull();
    });

    it('creates context with role', function (): void {
        $role = Mockery::mock(DocRole::class);
        $context = ViewerContext::withRole($role);

        expect($context->isOwner())->toBeFalse()
            ->and($context->isAnonymous())->toBeFalse()
            ->and($context->hasRole())->toBeTrue()
            ->and($context->role())->toBe($role);
    });
})->covers(ViewerContext::class);
