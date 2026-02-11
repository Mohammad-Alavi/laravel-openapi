<?php

declare(strict_types=1);

use App\Domain\Documentation\Access\Entities\DocRole;
use App\Domain\Documentation\Access\Enums\RuleType;
use App\Domain\Documentation\Access\ValueObjects\ScopeCollection;

describe(class_basename(DocRole::class), function (): void {
    it('grants access when scope matches tag', function (): void {
        $role = new DocRole();
        $role->forceFill([
            'project_id' => 1,
            'name' => 'Partner',
            'scopes' => ['payments', 'webhooks'],
            'is_default' => false,
        ]);

        expect($role->grantsAccessTo(RuleType::Tag, 'payments'))->toBeTrue()
            ->and($role->grantsAccessTo(RuleType::Tag, 'webhooks'))->toBeTrue()
            ->and($role->grantsAccessTo(RuleType::Tag, 'users'))->toBeFalse();
    });

    it('grants access with wildcard scopes', function (): void {
        $role = new DocRole();
        $role->forceFill([
            'project_id' => 1,
            'name' => 'V2 Access',
            'scopes' => ['/api/v2/*'],
            'is_default' => false,
        ]);

        expect($role->grantsAccessTo(RuleType::Path, '/api/v2/users'))->toBeTrue()
            ->and($role->grantsAccessTo(RuleType::Path, '/api/v1/users'))->toBeFalse();
    });

    it('returns scope collection', function (): void {
        $role = new DocRole();
        $role->forceFill([
            'project_id' => 1,
            'name' => 'Test',
            'scopes' => ['payments', 'webhooks'],
            'is_default' => false,
        ]);

        expect($role->getScopes())->toBeInstanceOf(ScopeCollection::class)
            ->and($role->getScopes()->count())->toBe(2);
    });

    it('exposes domain properties via contract methods', function (): void {
        $role = new DocRole();
        $role->forceFill([
            'id' => 1,
            'project_id' => 42,
            'name' => 'Admin',
            'scopes' => ['*'],
            'is_default' => true,
        ]);

        expect($role->getId())->toBe(1)
            ->and($role->getProjectId())->toBe(42)
            ->and($role->getName())->toBe('Admin')
            ->and($role->isDefault())->toBeTrue();
    });
})->covers(DocRole::class);
