<?php

use App\Domain\Documentation\Access\Enums\DocVisibility;

describe(class_basename(DocVisibility::class), function (): void {
    it('has expected cases', function (): void {
        expect(DocVisibility::cases())->toHaveCount(2)
            ->and(DocVisibility::Public->value)->toBe('public')
            ->and(DocVisibility::Private->value)->toBe('private');
    });

    it('can be created from value', function (string $value, DocVisibility $expected): void {
        expect(DocVisibility::from($value))->toBe($expected);
    })->with([
        ['public', DocVisibility::Public],
        ['private', DocVisibility::Private],
    ]);
})->covers(DocVisibility::class);
