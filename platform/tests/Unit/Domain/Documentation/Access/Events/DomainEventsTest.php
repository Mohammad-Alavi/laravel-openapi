<?php

use App\Domain\Documentation\Access\Events\AccessLinkCreated;
use App\Domain\Documentation\Access\Events\AccessLinkRevoked;
use App\Domain\Documentation\Access\Events\DocRoleCreated;
use App\Domain\Documentation\Access\Events\DocRoleDeleted;
use App\Domain\Documentation\Access\Events\DocRoleUpdated;
use App\Domain\Documentation\Access\Events\DocSettingUpdated;
use App\Domain\Documentation\Access\Events\VisibilityRuleCreated;
use App\Domain\Documentation\Access\Events\VisibilityRuleDeleted;
use App\Domain\Documentation\Access\Events\VisibilityRuleUpdated;

describe('Access domain events', function (): void {
    it('constructs DocSettingUpdated', function (): void {
        $event = new DocSettingUpdated(1, 'private', 'public');

        expect($event->projectId)->toBe(1)
            ->and($event->oldVisibility)->toBe('private')
            ->and($event->newVisibility)->toBe('public');
    });

    it('constructs DocRoleCreated', function (): void {
        $event = new DocRoleCreated(1, 'Partner', 3, true);

        expect($event->projectId)->toBe(1)
            ->and($event->roleName)->toBe('Partner')
            ->and($event->scopeCount)->toBe(3)
            ->and($event->hasWildcards)->toBeTrue();
    });

    it('constructs DocRoleUpdated', function (): void {
        $event = new DocRoleUpdated(1, 'Partner', true);

        expect($event->projectId)->toBe(1)
            ->and($event->roleName)->toBe('Partner')
            ->and($event->scopesChanged)->toBeTrue();
    });

    it('constructs DocRoleDeleted', function (): void {
        $event = new DocRoleDeleted(1, 'Partner');

        expect($event->projectId)->toBe(1)
            ->and($event->roleName)->toBe('Partner');
    });

    it('constructs VisibilityRuleCreated', function (): void {
        $event = new VisibilityRuleCreated(1, 'tag', 'payments', 'restricted');

        expect($event->projectId)->toBe(1)
            ->and($event->ruleType)->toBe('tag')
            ->and($event->identifier)->toBe('payments')
            ->and($event->visibility)->toBe('restricted');
    });

    it('constructs VisibilityRuleUpdated', function (): void {
        $event = new VisibilityRuleUpdated(1, 'tag', 'public', 'restricted');

        expect($event->projectId)->toBe(1)
            ->and($event->ruleType)->toBe('tag')
            ->and($event->oldVisibility)->toBe('public')
            ->and($event->newVisibility)->toBe('restricted');
    });

    it('constructs VisibilityRuleDeleted', function (): void {
        $event = new VisibilityRuleDeleted(1, 'tag', 'payments');

        expect($event->projectId)->toBe(1)
            ->and($event->ruleType)->toBe('tag')
            ->and($event->identifier)->toBe('payments');
    });

    it('constructs AccessLinkCreated', function (): void {
        $event = new AccessLinkCreated(1, 'Partner', true);

        expect($event->projectId)->toBe(1)
            ->and($event->roleName)->toBe('Partner')
            ->and($event->hasExpiry)->toBeTrue();
    });

    it('constructs AccessLinkRevoked', function (): void {
        $event = new AccessLinkRevoked(1, 'Partner Link');

        expect($event->projectId)->toBe(1)
            ->and($event->linkName)->toBe('Partner Link');
    });
})->covers(
    DocSettingUpdated::class,
    DocRoleCreated::class,
    DocRoleUpdated::class,
    DocRoleDeleted::class,
    VisibilityRuleCreated::class,
    VisibilityRuleUpdated::class,
    VisibilityRuleDeleted::class,
    AccessLinkCreated::class,
    AccessLinkRevoked::class,
);
