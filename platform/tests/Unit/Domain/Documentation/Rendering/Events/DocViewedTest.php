<?php

use App\Domain\Documentation\Rendering\Events\DocViewed;

describe(class_basename(DocViewed::class), function (): void {
    it('constructs with all properties', function (): void {
        $event = new DocViewed(1, 'role', 'Partner', 5);

        expect($event->projectId)->toBe(1)
            ->and($event->viewerType)->toBe('role')
            ->and($event->roleName)->toBe('Partner')
            ->and($event->endpointCount)->toBe(5);
    });

    it('allows null role name for anonymous viewers', function (): void {
        $event = new DocViewed(1, 'anonymous', null, 3);

        expect($event->roleName)->toBeNull()
            ->and($event->viewerType)->toBe('anonymous');
    });
})->covers(DocViewed::class);
