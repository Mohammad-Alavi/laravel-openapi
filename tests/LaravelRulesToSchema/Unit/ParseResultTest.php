<?php

declare(strict_types=1);

use MohammadAlavi\LaravelRulesToSchema\ParseResult;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(ParseResult::class), function (): void {
    it('creates schema result', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = ParseResult::single($schema);

        expect($result->isSchema())->toBeTrue()
            ->and($result->isExpanded())->toBeFalse()
            ->and($result->isExcluded())->toBeFalse()
            ->and($result->schema())->toBe($schema);
    });

    it('creates expanded result', function (): void {
        $schemas = [
            'password' => LooseFluentDescriptor::withoutSchema(),
            'password_confirmed' => LooseFluentDescriptor::withoutSchema(),
        ];

        $result = ParseResult::expanded($schemas);

        expect($result->isExpanded())->toBeTrue()
            ->and($result->isSchema())->toBeFalse()
            ->and($result->isExcluded())->toBeFalse()
            ->and($result->schemas())->toBe($schemas);
    });

    it('creates excluded result', function (): void {
        $result = ParseResult::excluded();

        expect($result->isExcluded())->toBeTrue()
            ->and($result->isSchema())->toBeFalse()
            ->and($result->isExpanded())->toBeFalse();
    });

    it('throws when accessing schema on excluded result', function (): void {
        ParseResult::excluded()->schema();
    })->throws(LogicException::class);

    it('throws when accessing schema on expanded result', function (): void {
        $schemas = ['a' => LooseFluentDescriptor::withoutSchema()];

        ParseResult::expanded($schemas)->schema();
    })->throws(LogicException::class);

    it('throws when accessing schemas on schema result', function (): void {
        ParseResult::single(LooseFluentDescriptor::withoutSchema())->schemas();
    })->throws(LogicException::class);

    it('throws when accessing schemas on excluded result', function (): void {
        ParseResult::excluded()->schemas();
    })->throws(LogicException::class);
})->covers(ParseResult::class);
