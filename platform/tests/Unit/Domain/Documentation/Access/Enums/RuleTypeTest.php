<?php

declare(strict_types=1);

use App\Domain\Documentation\Access\Enums\RuleType;

describe(class_basename(RuleType::class), function (): void {
    it('has expected cases', function (): void {
        expect(RuleType::cases())->toHaveCount(2)
            ->and(RuleType::Tag->value)->toBe('tag')
            ->and(RuleType::Path->value)->toBe('path');
    });

    it('can be created from value', function (string $value, RuleType $expected): void {
        expect(RuleType::from($value))->toBe($expected);
    })->with([
        ['tag', RuleType::Tag],
        ['path', RuleType::Path],
    ]);
})->covers(RuleType::class);
