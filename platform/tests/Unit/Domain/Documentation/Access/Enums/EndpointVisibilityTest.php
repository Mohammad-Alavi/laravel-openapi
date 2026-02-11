<?php

declare(strict_types=1);

use App\Domain\Documentation\Access\Enums\EndpointVisibility;

describe(class_basename(EndpointVisibility::class), function (): void {
    it('has expected cases', function (): void {
        expect(EndpointVisibility::cases())->toHaveCount(4)
            ->and(EndpointVisibility::Public->value)->toBe('public')
            ->and(EndpointVisibility::Internal->value)->toBe('internal')
            ->and(EndpointVisibility::Restricted->value)->toBe('restricted')
            ->and(EndpointVisibility::Hidden->value)->toBe('hidden');
    });

    it('can be created from value', function (string $value, EndpointVisibility $expected): void {
        expect(EndpointVisibility::from($value))->toBe($expected);
    })->with([
        ['public', EndpointVisibility::Public],
        ['internal', EndpointVisibility::Internal],
        ['restricted', EndpointVisibility::Restricted],
        ['hidden', EndpointVisibility::Hidden],
    ]);
})->covers(EndpointVisibility::class);
