<?php

namespace Tests\oooas\Unit\Support\SharedFields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;

describe(class_basename(Description::class), function (): void {
    it('can create description', function (): void {
        $description = Description::create('A detailed description');

        expect($description->value())->toBe('A detailed description')
            ->and($description->jsonSerialize())->toBe('A detailed description');
    });

    it('accepts markdown content', function (): void {
        $markdown = "# Title\n\nThis is **bold** and *italic* text.\n\n- List item 1\n- List item 2";
        $description = Description::create($markdown);

        expect($description->value())->toBe($markdown);
    });

    it('rejects empty description', function (): void {
        expect(fn () => Description::create(''))->toThrow(\InvalidArgumentException::class);
    });

    it('accepts whitespace-only as valid', function (): void {
        // Whitespace is considered valid content (notEmpty checks for empty string)
        expect(fn () => Description::create('   '))->not->toThrow(\InvalidArgumentException::class);
    });
})->covers(Description::class);
